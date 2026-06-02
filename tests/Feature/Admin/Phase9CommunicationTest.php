<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Modules\Communication\Models\Announcement;
use App\Modules\Communication\Models\Message;
use App\Modules\Communication\Models\Notification;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class Phase9CommunicationTest extends TestCase
{
    use RefreshDatabase;

    protected $superAdmin;

    protected $commAdmin;

    protected $otherUser;

    protected $unauthorizedUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);

        // Ensure all required permissions exist
        $permissions = ['communication.view', 'communication.create', 'communication.update', 'communication.delete'];
        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
        }

        // Super Admin
        $this->superAdmin = User::factory()->create();
        $this->superAdmin->assignRole('Super Admin');

        // Comm Admin
        $this->commAdmin = User::factory()->create();
        $role = Role::firstOrCreate(['name' => 'Admin Communication', 'guard_name' => 'web']);
        $role->syncPermissions($permissions);
        $this->commAdmin->assignRole($role);

        // Other User
        $this->otherUser = User::factory()->create();

        // Unauthorized User
        $this->unauthorizedUser = User::factory()->create();
    }

    // ===================== Step 1: Dashboard =====================

    #[Test]
    public function guest_cannot_access_communication_dashboard(): void
    {
        $response = $this->get(route('admin.communication.dashboard'));
        $response->assertRedirect('/login');
    }

    #[Test]
    public function user_without_communication_view_cannot_access_communication_dashboard(): void
    {
        $this->actingAs($this->unauthorizedUser);
        $response = $this->get(route('admin.communication.dashboard'));
        $response->assertStatus(403);
    }

    #[Test]
    public function user_with_communication_view_can_access_communication_dashboard(): void
    {
        $this->actingAs($this->commAdmin);
        $response = $this->get(route('admin.communication.dashboard'));
        $response->assertStatus(200);
        $response->assertSeeText('Komunikasi & Notifikasi');
    }

    #[Test]
    public function communication_dashboard_route_exists(): void
    {
        $this->assertTrue(Route::has('admin.communication.dashboard'));
    }

    #[Test]
    public function dashboard_shows_real_stats(): void
    {
        Announcement::create([
            'title' => 'Stat Check',
            'content' => 'Check contents',
            'target' => 'all',
            'priority' => 'normal',
            'is_published' => true,
            'published_at' => now(),
            'created_by' => $this->superAdmin->id,
        ]);

        $this->actingAs($this->commAdmin);
        $response = $this->get(route('admin.communication.dashboard'));
        $response->assertSeeText('1');
    }

    // ===================== Step 2: Announcements =====================

    #[Test]
    public function user_with_communication_view_can_access_announcement_index(): void
    {
        $this->actingAs($this->commAdmin);
        $response = $this->get(route('admin.communication.announcements.index'));
        $response->assertStatus(200);
    }

    #[Test]
    public function user_with_communication_create_can_create_draft_announcement(): void
    {
        $this->actingAs($this->commAdmin);
        $response = $this->post(route('admin.communication.announcements.store'), [
            'title' => 'Draft Announcement',
            'content' => 'Content here',
            'target' => 'all',
            'priority' => 'normal',
            'is_published' => 0,
        ]);

        $response->assertRedirect(route('admin.communication.announcements.index'));
        $this->assertDatabaseHas('announcements', [
            'title' => 'Draft Announcement',
            'is_published' => false,
        ]);
    }

    #[Test]
    public function validation_fails_when_announcement_title_is_missing(): void
    {
        $this->actingAs($this->commAdmin);
        $response = $this->post(route('admin.communication.announcements.store'), [
            'title' => '',
            'content' => 'Some content',
            'target' => 'all',
            'priority' => 'normal',
        ]);
        $response->assertSessionHasErrors('title');
    }

    #[Test]
    public function user_with_communication_view_can_access_announcement_show(): void
    {
        $announcement = Announcement::create([
            'title' => 'Visible Item',
            'content' => 'Detailed content',
            'target' => 'all',
            'priority' => 'normal',
            'is_published' => true,
            'created_by' => $this->superAdmin->id,
        ]);

        $this->actingAs($this->commAdmin);
        $response = $this->get(route('admin.communication.announcements.show', $announcement));
        $response->assertStatus(200)->assertSeeText('Visible Item');
    }

    #[Test]
    public function published_announcement_creates_user_notifications(): void
    {
        $studentRole = Role::firstOrCreate(['name' => 'Siswa', 'guard_name' => 'web']);
        $student = User::factory()->create();
        $student->assignRole($studentRole);

        $this->actingAs($this->commAdmin);
        $this->post(route('admin.communication.announcements.store'), [
            'title' => 'Broadcast Notice',
            'content' => 'Important news for students',
            'target' => 'students',
            'priority' => 'high',
            'is_published' => 1,
        ]);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $student->id,
            'type' => 'announcement',
            'title' => 'Pengumuman Baru: Broadcast Notice',
        ]);
    }

    #[Test]
    public function user_with_communication_delete_can_delete_draft_announcement(): void
    {
        $announcement = Announcement::create([
            'title' => 'To Delete',
            'content' => 'Content',
            'target' => 'all',
            'priority' => 'low',
            'is_published' => false,
        ]);

        $this->actingAs($this->commAdmin);
        $response = $this->delete(route('admin.communication.announcements.destroy', $announcement));

        $response->assertRedirect(route('admin.communication.announcements.index'));
        $this->assertDatabaseMissing('announcements', ['id' => $announcement->id]);
    }

    // ===================== Step 3: Internal Messaging =====================

    #[Test]
    public function user_with_communication_view_can_access_inbox(): void
    {
        $this->actingAs($this->commAdmin);
        $response = $this->get(route('admin.communication.messages.inbox'));
        $response->assertStatus(200);
    }

    #[Test]
    public function user_with_communication_view_can_access_outbox(): void
    {
        $this->actingAs($this->commAdmin);
        $response = $this->get(route('admin.communication.messages.outbox'));
        $response->assertStatus(200);
    }

    #[Test]
    public function user_with_communication_create_can_send_message_to_another_user(): void
    {
        $this->actingAs($this->commAdmin);
        $response = $this->post(route('admin.communication.messages.store'), [
            'recipient_id' => $this->otherUser->id,
            'subject' => 'Hello',
            'body' => 'Test message body',
        ]);

        $response->assertRedirect(route('admin.communication.messages.outbox'));
        $this->assertDatabaseHas('messages', [
            'sender_id' => $this->commAdmin->id,
            'recipient_id' => $this->otherUser->id,
            'subject' => 'Hello',
        ]);
    }

    #[Test]
    public function validation_fails_when_recipient_is_self(): void
    {
        $this->actingAs($this->commAdmin);
        $response = $this->post(route('admin.communication.messages.store'), [
            'recipient_id' => $this->commAdmin->id,
            'subject' => 'Self Message',
            'body' => 'Not allowed',
        ]);

        $response->assertSessionHasErrors('recipient_id');
    }

    #[Test]
    public function user_can_view_received_message(): void
    {
        $message = Message::create([
            'sender_id' => $this->otherUser->id,
            'recipient_id' => $this->commAdmin->id,
            'subject' => 'Secret',
            'body' => 'Hidden body',
        ]);

        $this->actingAs($this->commAdmin);
        $response = $this->get(route('admin.communication.messages.show', $message));
        $response->assertStatus(200)->assertSeeText('Hidden body');

        // Verify mark as read automatically
        $message->refresh();
        $this->assertTrue($message->is_read);
        $this->assertNotNull($message->read_at);
    }

    #[Test]
    public function user_cannot_view_message_that_is_not_theirs(): void
    {
        $message = Message::create([
            'sender_id' => $this->superAdmin->id,
            'recipient_id' => $this->otherUser->id,
            'subject' => 'Private',
            'body' => 'Private body',
        ]);

        $this->actingAs($this->commAdmin);
        $response = $this->get(route('admin.communication.messages.show', $message));
        $response->assertStatus(403);
    }

    #[Test]
    public function user_can_mark_received_message_as_read(): void
    {
        $message = Message::create([
            'sender_id' => $this->otherUser->id,
            'recipient_id' => $this->commAdmin->id,
            'subject' => 'Unread',
            'body' => 'Body',
            'is_read' => false,
        ]);

        $this->actingAs($this->commAdmin);
        $response = $this->post(route('admin.communication.messages.mark-read', $message));
        $response->assertStatus(302);

        $message->refresh();
        $this->assertTrue($message->is_read);
    }

    #[Test]
    public function user_cannot_mark_other_users_message_as_read(): void
    {
        $message = Message::create([
            'sender_id' => $this->superAdmin->id,
            'recipient_id' => $this->otherUser->id,
            'subject' => 'Unread',
            'body' => 'Body',
            'is_read' => false,
        ]);

        $this->actingAs($this->commAdmin);
        $response = $this->post(route('admin.communication.messages.mark-read', $message));
        $response->assertStatus(403);
    }

    #[Test]
    public function dashboard_unread_message_count_updates(): void
    {
        $this->actingAs($this->commAdmin);
        $responseBefore = $this->get(route('admin.communication.dashboard'));
        $responseBefore->assertSeeText('0'); // unread messages

        Message::create([
            'sender_id' => $this->otherUser->id,
            'recipient_id' => $this->commAdmin->id,
            'subject' => 'New Message',
            'body' => 'Body',
            'is_read' => false,
        ]);

        $responseAfter = $this->get(route('admin.communication.dashboard'));
        $responseAfter->assertSeeText('1');
    }

    #[Test]
    public function user_with_communication_delete_can_delete_own_message(): void
    {
        $message = Message::create([
            'sender_id' => $this->otherUser->id,
            'recipient_id' => $this->commAdmin->id,
            'subject' => 'To Delete',
            'body' => 'Body',
        ]);

        $this->actingAs($this->commAdmin);
        $this->delete(route('admin.communication.messages.destroy', $message))->assertStatus(302);

        $this->assertDatabaseMissing('messages', ['id' => $message->id]);
    }

    // ===================== Step 4: Notification Center =====================

    #[Test]
    public function user_with_communication_view_can_access_notification_center(): void
    {
        $this->actingAs($this->commAdmin);
        $response = $this->get(route('admin.communication.notifications.index'));
        $response->assertStatus(200);
        $response->assertSeeText('Pusat Notifikasi');
    }

    #[Test]
    public function notification_center_shows_only_current_users_notifications(): void
    {
        Notification::create([
            'user_id' => $this->commAdmin->id,
            'type' => 'system',
            'title' => 'Admin Notif',
            'message' => 'Hello admin',
        ]);

        Notification::create([
            'user_id' => $this->otherUser->id,
            'type' => 'system',
            'title' => 'Other Notif',
            'message' => 'Hello other',
        ]);

        $this->actingAs($this->commAdmin);
        $response = $this->get(route('admin.communication.notifications.index'));

        $response->assertSeeText('Admin Notif');
        $response->assertDontSeeText('Other Notif');
    }

    #[Test]
    public function user_can_view_own_notification(): void
    {
        $notification = Notification::create([
            'user_id' => $this->commAdmin->id,
            'type' => 'system',
            'title' => 'My Notif',
            'message' => 'Details here',
        ]);

        $this->actingAs($this->commAdmin);
        $response = $this->get(route('admin.communication.notifications.show', $notification));
        $response->assertStatus(200)->assertSeeText('Details here');

        $notification->refresh();
        $this->assertTrue($notification->is_read);
        $this->assertNotNull($notification->read_at);
    }

    #[Test]
    public function user_cannot_view_other_users_notification(): void
    {
        $notification = Notification::create([
            'user_id' => $this->otherUser->id,
            'type' => 'system',
            'title' => 'Other Notif',
            'message' => 'Private',
        ]);

        $this->actingAs($this->commAdmin);
        $response = $this->get(route('admin.communication.notifications.show', $notification));
        $response->assertStatus(403);
    }

    #[Test]
    public function user_can_mark_own_notification_as_read(): void
    {
        $notification = Notification::create([
            'user_id' => $this->commAdmin->id,
            'type' => 'system',
            'title' => 'Unread',
            'message' => 'Msg',
            'is_read' => false,
        ]);

        $this->actingAs($this->commAdmin);
        $this->post(route('admin.communication.notifications.mark-read', $notification))->assertStatus(302);

        $notification->refresh();
        $this->assertTrue($notification->is_read);
    }

    #[Test]
    public function user_can_mark_all_own_notifications_as_read(): void
    {
        Notification::create(['user_id' => $this->commAdmin->id, 'type' => 's', 'title' => 'N1', 'message' => 'm', 'is_read' => false]);
        Notification::create(['user_id' => $this->commAdmin->id, 'type' => 's', 'title' => 'N2', 'message' => 'm', 'is_read' => false]);
        Notification::create(['user_id' => $this->otherUser->id, 'type' => 's', 'title' => 'N3', 'message' => 'm', 'is_read' => false]);

        $this->actingAs($this->commAdmin);
        $this->post(route('admin.communication.notifications.mark-all-read'))->assertStatus(302);

        $this->assertEquals(0, Notification::where('user_id', $this->commAdmin->id)->where('is_read', false)->count());
        $this->assertEquals(1, Notification::where('user_id', $this->otherUser->id)->where('is_read', false)->count());
    }

    #[Test]
    public function user_with_communication_delete_can_delete_own_notification(): void
    {
        $notification = Notification::create([
            'user_id' => $this->commAdmin->id,
            'type' => 'system',
            'title' => 'To Delete',
            'message' => 'Msg',
        ]);

        $this->actingAs($this->commAdmin);
        $this->delete(route('admin.communication.notifications.destroy', $notification))->assertStatus(302);

        $this->assertDatabaseMissing('notifications', ['id' => $notification->id]);
    }

    #[Test]
    public function dashboard_unread_notification_count_updates(): void
    {
        $this->actingAs($this->commAdmin);
        $responseBefore = $this->get(route('admin.communication.dashboard'));
        $responseBefore->assertSeeText('0'); // unread notifications

        Notification::create([
            'user_id' => $this->commAdmin->id,
            'type' => 'system',
            'title' => 'New',
            'message' => 'Msg',
            'is_read' => false,
        ]);

        $responseAfter = $this->get(route('admin.communication.dashboard'));
        $responseAfter->assertSeeText('1');
    }

    #[Test]
    public function notification_routes_exist(): void
    {
        $this->assertTrue(Route::has('admin.communication.notifications.index'));
        $this->assertTrue(Route::has('admin.communication.notifications.mark-all-read'));
        $this->assertTrue(Route::has('admin.communication.notifications.show'));
        $this->assertTrue(Route::has('admin.communication.notifications.mark-read'));
        $this->assertTrue(Route::has('admin.communication.notifications.destroy'));
    }
}
