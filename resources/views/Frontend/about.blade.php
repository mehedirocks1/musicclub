@extends('frontend.layout')

@section('title', $about->title . ' — POJ Music Club')

@section('content')
<section class="relative bg-gradient-to-b from-black to-emerald-950 pt-28 pb-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @includeIf('frontend.partials.breadcrumb', ['title' => 'About Club'])

        <div class="mt-8 grid lg:grid-cols-2 gap-10 items-center">
            <div>
                <h1 class="text-4xl md:text-5xl font-extrabold text-gradient-yellow text-shadow-yellow-lg">
                    About {{ $about->title }}
                </h1>
                
                <p class="mt-6 text-gray-300 leading-relaxed">
                    {{ $about->short_description }}
                </p>

                <div class="mt-8 grid sm:grid-cols-3 gap-4">
                    <div class="glass-morphism p-5 rounded-xl">
                        <p class="text-3xl font-bold text-yellow-500">{{ $about->founded_year }}</p>
                        <p class="text-gray-400">Founded</p>
                    </div>
                    
                    <div class="glass-morphism p-5 rounded-xl">
                        <p class="text-3xl font-bold text-yellow-500">{{ number_format($about->members_count / 1000, 1) }}K+</p>
                        <p class="text-gray-400">Members</p>
                    </div>
                    
                    <div class="glass-morphism p-5 rounded-xl">
                        <p class="text-3xl font-bold text-yellow-500">{{ $about->events_per_year }}+</p>
                        <p class="text-gray-400">Events / Yr</p>
                    </div>
                </div>
            </div>

            <div class="glass-morphism rounded-2xl p-6 perspective-card">
                @if ($about->hero_image)
                    <img class="rounded-xl w-full h-80 object-cover" 
                         src="{{ Storage::url($about->hero_image) }}" 
                         alt="{{ $about->title }} Hero Image">
                @else
                    <img class="rounded-xl w-full h-80 object-cover" 
                         src="https://via.placeholder.com/1200x800.png?text=Placeholder+Image" 
                         alt="Placeholder Image">
                @endif
            </div>
        </div>

        <div class="mt-14 grid lg:grid-cols-3 gap-6">
            <div class="glass-morphism p-6 rounded-xl">
                <h3 class="text-xl font-bold text-yellow-500 mb-2">Our Mission</h3>
                <p class="text-gray-300">{{ $about->mission }}</p>
            </div>
            
            <div class="glass-morphism p-6 rounded-xl">
                <h3 class="text-xl font-bold text-yellow-500 mb-2">Our Vision</h3>
                <p class="text-gray-300">{{ $about->vision }}</p>
            </div>
            
            <div class="glass-morphism p-6 rounded-xl">
                <h3 class="text-xl font-bold text-yellow-500 mb-2">What We Do</h3>
                <ul class="text-gray-300 list-disc pl-5 space-y-1">
                    @forelse ($about->activities ?? [] as $activity)
                        <li>{{ $activity }}</li>
                    @empty
                        <li>No activities listed yet.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</section>
@endsection
