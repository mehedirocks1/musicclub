@extends('frontend.layout')

@section('title', 'Branches — POJ Music Club')

@section('content')
<section class="relative bg-gradient-to-b from-black to-emerald-950 pt-28 pb-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @includeIf('frontend.partials.breadcrumb', ['title' => 'Branch'])

        <h1 class="mt-6 text-4xl md:text-5xl font-extrabold text-gradient-yellow text-shadow-yellow-lg text-center">Our Branches</h1>
        <p class="text-gray-300 text-center mt-4">We’re expanding across the city to make music more accessible.</p>

        <div class="mt-12 grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
            $branches = [
                ['name'=>'Tejgaon','address'=>'Evergreen Plaza, 260/B, Tejgaon I/A, Dhaka','phone'=>'+8801XXXXXXXXX'],
                ['name'=>'Dhanmondi','address'=>'Road 4/A, Dhanmondi, Dhaka','phone'=>'+8801XXXXXXXXX'],
                ['name'=>'Uttara','address'=>'Sector 7, Uttara, Dhaka','phone'=>'+8801XXXXXXXXX'],
            ];
            @endphp

            @foreach($branches as $b)
            <div class="glass-morphism p-6 rounded-xl perspective-card">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center"><i class="fas fa-location-dot text-black text-xl"></i></div>
                    <div>
                        <h3 class="text-xl font-bold text-yellow-500">{{ $b['name'] }}</h3>
                        <p class="text-gray-400">{{ $b['address'] }}</p>
                        <p class="text-gray-400">{{ $b['phone'] }}</p>
                    </div>
                </div>
                <a href="#" class="inline-flex items-center gap-2 mt-4 text-yellow-500 hover:text-yellow-400">
                    View on Map <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            @endforeach
        </div>

        {{-- Simple Map Placeholder --}}
        <div class="mt-12 glass-morphism rounded-2xl p-3">
            <div class="aspect-video w-full rounded-xl overflow-hidden">
                <iframe class="w-full h-full" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                    src="https://maps.google.com/maps?q=Tejgaon%20Industrial%20Area%20Dhaka&t=&z=13&ie=UTF8&iwloc=&output=embed"></iframe>
            </div>
        </div>
    </div>
</section>
@endsection
