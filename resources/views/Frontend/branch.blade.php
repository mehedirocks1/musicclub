@extends('frontend.layout')

@section('title', 'Branches — POJ Music Club')

@section('content')
<section class="relative bg-gradient-to-b from-black to-emerald-950 pt-28 pb-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @includeIf('frontend.partials.breadcrumb', ['title' => 'Branch'])

        <h1 class="mt-6 text-4xl md:text-5xl font-extrabold text-gradient-yellow text-shadow-yellow-lg text-center">
            Our Branches
        </h1>
        <p class="text-gray-300 text-center mt-4">We’re expanding across the city to make music more accessible.</p>

        <div class="mt-12 grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($branches as $branch)
            <div class="glass-morphism p-6 rounded-xl perspective-card">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-location-dot text-black text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-yellow-500">{{ $branch->name }}</h3>
                        <p class="text-gray-400">{{ $branch->address }}</p>
                        <p class="text-gray-400">{{ $branch->phone }}</p>
                    </div>
                </div>
                @if($branch->map_url)
                <a href="{{ route('branch.show', $branch) }}" class="inline-flex items-center gap-2 mt-4 text-yellow-500 hover:text-yellow-400">
                    View on Map <i class="fas fa-arrow-right"></i>
                </a>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
