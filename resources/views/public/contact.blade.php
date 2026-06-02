<x-public-layout title="Hubungi Kami">
    <div class="bg-slate-900 py-16 text-white text-center">
        <h1 class="text-4xl font-bold">Hubungi Kami</h1>
        <p class="mt-4 text-slate-400">Punya pertanyaan? Kami siap membantu Anda.</p>
    </div>

    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 mb-8">Informasi Kontak</h2>
                    <div class="space-y-8">
                        <div class="flex items-start gap-4">
                            <div class="h-12 w-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                                📍
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900">Alamat</h4>
                                <p class="text-slate-600 mt-1 leading-relaxed">{{ $settings->school_address ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="h-12 w-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                                📞
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900">Telepon</h4>
                                <p class="text-slate-600 mt-1">{{ $settings->school_phone ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="h-12 w-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                ✉️
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900">Email</h4>
                                <p class="text-slate-600 mt-1">{{ $settings->school_email ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-12 p-8 rounded-3xl bg-slate-50 border border-slate-100">
                        <h4 class="font-bold text-slate-900 mb-4">Jam Operasional</h4>
                        <ul class="space-y-2 text-sm text-slate-600">
                            <li class="flex justify-between"><span>Senin - Kamis</span> <span class="font-medium">07:00 - 15:30</span></li>
                            <li class="flex justify-between"><span>Jumat</span> <span class="font-medium">07:00 - 14:00</span></li>
                            <li class="flex justify-between text-slate-400"><span>Sabtu - Minggu</span> <span class="font-medium text-red-400">Libur</span></li>
                        </ul>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-8 shadow-2xl shadow-slate-100 ring-1 ring-slate-100">
                    <h2 class="text-2xl font-bold text-slate-900 mb-6">Kirim Pesan</h2>
                    <form action="#" method="POST" class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Nama Lengkap</label>
                                <input type="text" class="w-full rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 text-sm py-3" placeholder="Masukkan nama...">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                                <input type="email" class="w-full rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 text-sm py-3" placeholder="nama@email.com">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Subjek</label>
                            <input type="text" class="w-full rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 text-sm py-3" placeholder="Apa yang ingin ditanyakan?">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Pesan</label>
                            <textarea rows="5" class="w-full rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="Tuliskan detail pesan Anda..."></textarea>
                        </div>
                        <button type="button" class="w-full bg-indigo-600 text-white rounded-xl py-4 font-bold text-sm hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-100">
                            Kirim Pesan Sekarang
                        </button>
                        <p class="text-center text-xs text-slate-400 italic">Pesan Anda akan segera kami respon melalui email.</p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-public-layout>
