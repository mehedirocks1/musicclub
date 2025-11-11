@extends('frontend.layout')

@section('title', $branch->name . ' — POJ Music Club')

@section('content')
<section class="relative bg-gradient-to-b from-black to-emerald-950 pt-28 pb-16">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        @includeIf('frontend.partials.breadcrumb', ['title' => $branch->name])

        <h1 class="mt-6 text-4xl md:text-5xl font-extrabold text-gradient-yellow text-shadow-yellow-lg text-center">
            {{ $branch->name }}
        </h1>

        <div class="mt-8 text-center text-gray-300">
            <p><strong>Address:</strong> {{ $branch->address }}</p>
            <p><strong>Phone:</strong> {{ $branch->phone }}</p>
        </div>

        @if($branch->map_url)
        <div class="mt-12 glass-morphism rounded-2xl p-3">
            <div class="aspect-video w-full rounded-xl overflow-hidden">
                <iframe class="w-full h-full" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                    src="{{ $branch->map_url }}"></iframe>
            </div>
        </div>
        @endif

        <div class="mt-6 text-center">
            <a href="{{ route('branches') }}" class="inline-block px-6 py-2 bg-yellow-500 text-black font-semibold rounded-lg hover:bg-yellow-400">
                Back to All Branches
            </a>
        </div>
    </div>
</section>
@endsection
