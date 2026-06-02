<?php

namespace App\Modules\Communication\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Communication\Models\Message;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function inbox(Request $request): View
    {
        if (Gate::denies('communication.view')) {
            abort(403);
        }

        $messages = Message::with('sender')
            ->where('recipient_id', auth()->id())
            ->latest()
            ->paginate(15);

        return view('modules.communication.messages.inbox', compact('messages'));
    }

    public function outbox(Request $request): View
    {
        if (Gate::denies('communication.view')) {
            abort(403);
        }

        $messages = Message::with('recipient')
            ->where('sender_id', auth()->id())
            ->latest()
            ->paginate(15);

        return view('modules.communication.messages.outbox', compact('messages'));
    }

    public function create(): View
    {
        if (Gate::denies('communication.create')) {
            abort(403);
        }

        $users = User::where('id', '!=', auth()->id())->orderBy('name')->get();

        return view('modules.communication.messages.create', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {
        if (Gate::denies('communication.create')) {
            abort(403);
        }

        // Merge sender_id into request for validation
        $request->merge(['sender_id' => auth()->id()]);

        $validated = $request->validate([
            'sender_id' => ['required', 'exists:users,id'],
            'recipient_id' => ['required', 'exists:users,id', 'different:sender_id'],
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ], [
            'recipient_id.different' => 'Anda tidak dapat mengirim pesan ke diri sendiri.',
        ]);

        $validated['is_read'] = false;

        Message::create($validated);

        return redirect()->route('admin.communication.messages.outbox')
            ->with('success', 'Pesan berhasil dikirim.');
    }

    public function show(Message $message): View
    {
        if (Gate::denies('communication.view')) {
            abort(403);
        }

        // Privacy check
        if ($message->sender_id !== auth()->id() && $message->recipient_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke pesan ini.');
        }

        // Mark as read if user is the recipient
        if ($message->recipient_id === auth()->id() && ! $message->is_read) {
            $message->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

        return view('modules.communication.messages.show', compact('message'));
    }

    public function markRead(Message $message): RedirectResponse
    {
        if (Gate::denies('communication.update')) {
            abort(403);
        }

        if ($message->recipient_id !== auth()->id()) {
            abort(403);
        }

        $message->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return back()->with('success', 'Pesan ditandai sebagai terbaca.');
    }

    public function destroy(Message $message): RedirectResponse
    {
        if (Gate::denies('communication.delete')) {
            abort(403);
        }

        // Can only delete own messages (either sent or received)
        if ($message->sender_id !== auth()->id() && $message->recipient_id !== auth()->id()) {
            abort(403);
        }

        $message->delete();

        return redirect()->route('admin.communication.messages.inbox')
            ->with('success', 'Pesan berhasil dihapus.');
    }
}
