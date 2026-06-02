<x-public-layout title="{{ $article->title }}">
    <div class="py-12 bg-white flex-1">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex mb-8 text-sm text-slate-500" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2">
                    <li><a href="{{ route('public.home') }}" class="hover:text-blue-600">Beranda</a></li>
                    <li><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></li>
                    <li><a href="{{ route('public.news') }}" class="hover:text-blue-600">Berita</a></li>
                    <li><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></li>
                    <li class="truncate font-medium text-slate-900" aria-current="page">{{ $article->title }}</li>
                </ol>
            </nav>

            <article>
                <header class="mb-10">
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-slate-900 leading-tight mb-6">
                        {{ $article->title }}
                    </h1>
                    <div class="flex items-center gap-4 text-sm text-slate-500 border-b border-slate-100 pb-6">
                        <div class="flex items-center gap-1.5">
                            <span class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center text-blue-600 font-bold text-xs">
                                {{ substr($article->author ?? 'A', 0, 1) }}
                            </span>
                            <span class="font-medium text-slate-700">{{ $article->author ?? 'Admin' }}</span>
                        </div>
                        <span>&bull;</span>
                        <time datetime="{{ $article->published_at ?? $article->created_at }}">
                            {{ $article->published_at?->format('d M Y') ?? $article->created_at->format('d M Y') }}
                        </time>
                    </div>
                </header>

                @if($article->image_path)
                    <div class="mb-10 rounded-2xl overflow-hidden shadow-lg ring-1 ring-slate-200">
                        <img src="{{ Storage::url($article->image_path) }}" alt="{{ $article->title }}" class="w-full h-auto">
                    </div>
                @endif

                <div class="prose prose-indigo prose-lg max-w-none text-slate-700 leading-relaxed">
                    {!! nl2br(e($article->content)) !!}
                </div>
            </article>

            <div class="mt-16 pt-10 border-t border-slate-100">
                <h3 class="font-bold text-slate-900 mb-6">Bagikan Berita</h3>
                <div class="flex gap-3">
                    <button class="rounded-full bg-slate-100 px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-200 transition-colors">Copy Link</button>
                    {{-- Social icons placeholder --}}
                </div>
            </div>
        </div>
    </div>
</x-public-layout>
