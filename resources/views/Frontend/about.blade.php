@extends('frontend.layout')

@section('title', 'About Club — POJ Music Club')

@section('content')
<section class="relative bg-gradient-to-b from-black to-emerald-950 pt-28 pb-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @includeIf('frontend.partials.breadcrumb', ['title' => 'About Club'])

        <div class="mt-8 grid lg:grid-cols-2 gap-10 items-center">
            <div>
                <h1 class="text-4xl md:text-5xl font-extrabold text-gradient-yellow text-shadow-yellow-lg">About POJ Music Club</h1>
                <p class="mt-6 text-gray-300 leading-relaxed">
                    POJ Music Club is a creative hub where musicians, learners, and fans connect. We host live sessions, workshops,
                    and curated programs to nurture musical talent and build a vibrant community.
                </p>

                <div class="mt-8 grid sm:grid-cols-3 gap-4">
                    <div class="glass-morphism p-5 rounded-xl">
                        <p class="text-3xl font-bold text-yellow-500">2017</p>
                        <p class="text-gray-400">Founded</p>
                    </div>
                    <div class="glass-morphism p-5 rounded-xl">
                        <p class="text-3xl font-bold text-yellow-500">2K+</p>
                        <p class="text-gray-400">Members</p>
                    </div>
                    <div class="glass-morphism p-5 rounded-xl">
                        <p class="text-3xl font-bold text-yellow-500">120+</p>
                        <p class="text-gray-400">Events / Yr</p>
                    </div>
                </div>
            </div>

            <div class="glass-morphism rounded-2xl p-6 perspective-card">
                <img class="rounded-xl w-full h-80 object-cover" src="https://images.unsplash.com/photo-1506157786151-b8491531f063?w=1200&q=80&auto=format&fit=crop" alt="Band performing on stage">
            </div>
        </div>

        <div class="mt-14 grid lg:grid-cols-3 gap-6">
            <div class="glass-morphism p-6 rounded-xl">
                <h3 class="text-xl font-bold text-yellow-500 mb-2">Our Mission</h3>
                <p class="text-gray-300">Inspire musical excellence through accessible learning and performance opportunities.</p>
            </div>
            <div class="glass-morphism p-6 rounded-xl">
                <h3 class="text-xl font-bold text-yellow-500 mb-2">Our Vision</h3>
                <p class="text-gray-300">A thriving music ecosystem where everyone can create, perform, and grow.</p>
            </div>
            <div class="glass-morphism p-6 rounded-xl">
                <h3 class="text-xl font-bold text-yellow-500 mb-2">What We Do</h3>
                <ul class="text-gray-300 list-disc pl-5 space-y-1">
                    <li>Live shows & jam nights</li>
                    <li>Workshops & masterclasses</li>
                    <li>Student showcases</li>
                    <li>Community projects & collaborations</li>
                </ul>
            </div>
        </div>
    </div>
</section>
@endsection
