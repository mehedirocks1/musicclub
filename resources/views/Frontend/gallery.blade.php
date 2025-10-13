@extends('frontend.layout')

@section('title', 'Gallery — POJ Music Club')

@section('content')
<section class="relative bg-gradient-to-b from-black to-emerald-950 pt-28 pb-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @includeIf('frontend.partials.breadcrumb', ['title' => 'Gallery'])

        <h1 class="mt-6 text-4xl md:text-5xl font-extrabold text-gradient-yellow text-shadow-yellow-lg text-center">Gallery</h1>
        <p class="text-gray-300 text-center mt-4">Highlights from our shows, workshops, and member moments.</p>

        {{-- Filter --}}
        <div class="mt-8 flex flex-wrap items-center justify-center gap-2">
            <button data-filter="all" class="gallery-filter px-4 py-2 rounded-lg glass-morphism hover:border-yellow-500">All</button>
            <button data-filter="live" class="gallery-filter px-4 py-2 rounded-lg glass-morphism hover:border-yellow-500">Live</button>
            <button data-filter="workshop" class="gallery-filter px-4 py-2 rounded-lg glass-morphism hover:border-yellow-500">Workshop</button>
            <button data-filter="members" class="gallery-filter px-4 py-2 rounded-lg glass-morphism hover:border-yellow-500">Members</button>
        </div>

        {{-- Masonry --}}
        <div id="galleryGrid" class="mt-8 columns-1 sm:columns-2 lg:columns-3 gap-4 [column-fill:_balance]">
            @php
            $items = [
                ['src'=>'https://images.unsplash.com/photo-1511379938547-c1f69419868d?q=80&w=1200&auto=format&fit=crop','cat'=>'live','alt'=>'Live guitar'],
                ['src'=>'https://images.unsplash.com/photo-1483412033650-1015ddeb83d1?q=80&w=1200&auto=format&fit=crop','cat'=>'workshop','alt'=>'Workshop keyboard'],
                ['src'=>'https://images.unsplash.com/photo-1511379938547-0f3f5c8bd0f9?q=80&w=1200&auto=format&fit=crop','cat'=>'members','alt'=>'Members jam'],
                ['src'=>'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?q=80&w=1200&auto=format&fit=crop','cat'=>'live','alt'=>'Stage lights'],
                ['src'=>'https://images.unsplash.com/photo-1465847899084-d164df4dedc6?q=80&w=1200&auto=format&fit=crop','cat'=>'workshop','alt'=>'Drums'],
                ['src'=>'https://images.unsplash.com/photo-1497032628192-86f99bcd76bc?q=80&w=1200&auto=format&fit=crop','cat'=>'members','alt'=>'Crowd'],
            ];
            @endphp
            @foreach($items as $i)
            <figure class="break-inside-avoid mb-4 overflow-hidden rounded-xl group cursor-pointer" data-cat="{{ $i['cat'] }}">
                <img src="{{ $i['src'] }}" alt="{{ $i['alt'] }}" class="w-full object-cover transition-transform duration-300 group-hover:scale-105">
            </figure>
            @endforeach
        </div>
    </div>

    {{-- Lightbox --}}
    <div id="lightbox" class="fixed inset-0 bg-black/90 z-50 hidden items-center justify-center p-6">
        <button id="lightboxClose" class="absolute top-6 right-6 w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center">
            <i class="fas fa-xmark text-white text-2xl"></i>
        </button>
        <img id="lightboxImg" src="" alt="Preview" class="max-h-[85vh] max-w-[90vw] rounded-xl">
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Filter
    const filters = document.querySelectorAll('.gallery-filter');
    const items = document.querySelectorAll('#galleryGrid figure');
    filters.forEach(btn => btn.addEventListener('click', () => {
        const cat = btn.dataset.filter;
        items.forEach(it => {
            it.classList.toggle('hidden', cat !== 'all' && it.dataset.cat !== cat);
        });
    }));

    // Lightbox
    const lb = document.getElementById('lightbox');
    const lbImg = document.getElementById('lightboxImg');
    const lbClose = document.getElementById('lightboxClose');
    document.getElementById('galleryGrid').addEventListener('click', (e) => {
        const img = e.target.closest('figure')?.querySelector('img');
        if (!img) return;
        lbImg.src = img.src;
        lb.classList.remove('hidden');
        lb.classList.add('flex');
    });
    lbClose.addEventListener('click', () => { lb.classList.add('hidden'); lb.classList.remove('flex'); });
    lb.addEventListener('click', (e) => { if (e.target === lb) lbClose.click(); });
});
</script>
@endpush
