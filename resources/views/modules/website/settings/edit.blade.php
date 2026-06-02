<x-admin-layout heading="Pengaturan Profil & Website">
    <form method="POST" action="{{ route('admin.website.settings.update') }}" enctype="multipart/form-data" class="max-w-6xl mx-auto space-y-6 pb-12">
        @csrf @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left Column: School Identity --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 space-y-5">
                    <h3 class="font-bold text-slate-900 border-b pb-3 text-sm uppercase tracking-wider">Identitas Sekolah</h3>
                    <x-admin.form-input name="school_name" label="Nama Sekolah" :value="old('school_name', $settings->school_name)" required />
                    <x-admin.form-input name="tagline" label="Tagline / Slogan" :value="old('tagline', $settings->tagline)" placeholder="Contoh: Unggul dalam Prestasi, Terpuji dalam Pekerti" />
                    <x-admin.form-input name="principal_name" label="Nama Kepala Sekolah" :value="old('principal_name', $settings->principal_name)" />
                    <x-admin.form-textarea name="principal_message" label="Sambutan Kepala Sekolah" rows="6">{{ old('principal_message', $settings->principal_message) }}</x-admin.form-textarea>
                </div>

                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 space-y-5">
                    <h3 class="font-bold text-slate-900 border-b pb-3 text-sm uppercase tracking-wider">Visi, Misi & Sejarah</h3>
                    <x-admin.form-textarea name="history" label="Sejarah Singkat" rows="6">{{ old('history', $settings->history) }}</x-admin.form-textarea>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-admin.form-textarea name="vision" label="Visi" rows="4">{{ old('vision', $settings->vision) }}</x-admin.form-textarea>
                        <x-admin.form-textarea name="mission" label="Misi" rows="4">{{ old('mission', $settings->mission) }}</x-admin.form-textarea>
                    </div>
                </div>
            </div>

            {{-- Right Column: Contact & Media --}}
            <div class="lg:col-span-1 space-y-6">
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 space-y-5">
                    <h3 class="font-bold text-slate-900 border-b pb-3 text-sm uppercase tracking-wider">Kontak & Lokasi</h3>
                    <x-admin.form-input name="school_email" label="Email Sekolah" type="email" :value="old('school_email', $settings->school_email)" />
                    <x-admin.form-input name="school_phone" label="Telepon Sekolah" :value="old('school_phone', $settings->school_phone)" />
                    <x-admin.form-textarea name="school_address" label="Alamat Lengkap" rows="3">{{ old('school_address', $settings->school_address) }}</x-admin.form-textarea>
                    <x-admin.form-input name="website_url" label="URL Website" :value="old('website_url', $settings->website_url)" />
                </div>

                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 space-y-5">
                    <h3 class="font-bold text-slate-900 border-b pb-3 text-sm uppercase tracking-wider">Media Sosial</h3>
                    <x-admin.form-input name="facebook_url" label="Facebook" :value="old('facebook_url', $settings->facebook_url)" placeholder="https://facebook.com/..." />
                    <x-admin.form-input name="instagram_url" label="Instagram" :value="old('instagram_url', $settings->instagram_url)" placeholder="https://instagram.com/..." />
                    <x-admin.form-input name="youtube_url" label="YouTube" :value="old('youtube_url', $settings->youtube_url)" placeholder="https://youtube.com/..." />
                </div>

                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 space-y-5">
                    <h3 class="font-bold text-slate-900 border-b pb-3 text-sm uppercase tracking-wider">Logo & Favicon</h3>
                    <div class="space-y-4">
                        <div>
                            @if($settings->logo_path)
                                <img src="{{ Storage::url($settings->logo_path) }}" alt="Logo" class="h-16 w-auto mb-2 rounded border p-1">
                            @endif
                            <x-admin.form-input name="logo" label="Logo Sekolah" type="file" accept="image/*" />
                        </div>
                        <div>
                            @if($settings->favicon_path)
                                <img src="{{ Storage::url($settings->favicon_path) }}" alt="Favicon" class="h-8 w-8 mb-2 rounded border p-1">
                            @endif
                            <x-admin.form-input name="favicon" label="Favicon" type="file" accept="image/*" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="rounded-lg bg-indigo-600 px-8 py-3 text-sm font-bold text-white transition hover:bg-indigo-700 shadow-md shadow-indigo-200">
                Simpan Semua Pengaturan
            </button>
        </div>
    </form>
</x-admin-layout>
