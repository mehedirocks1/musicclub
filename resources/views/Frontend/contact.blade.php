@extends('frontend.layout')

@section('title', 'Contact Us — POJ Music Club')

@section('content')
<section class="relative bg-gradient-to-b from-black to-emerald-950 pt-28 pb-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @includeIf('frontend.partials.breadcrumb', ['title' => 'Contact Us'])

        <div class="mt-8 grid lg:grid-cols-2 gap-10">
            {{-- Contact Form --}}
            <div class="glass-morphism p-6 rounded-2xl">
                <h2 class="text-2xl font-bold text-yellow-500 mb-4">Send us a message</h2>
                <form action="#" method="post" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm mb-2">Full Name</label>
                        <input type="text" name="name" class="w-full px-4 py-3 bg-black/50 border border-emerald-700 rounded-lg focus:border-yellow-500 focus:ring-yellow-500/50 outline-none">
                    </div>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm mb-2">Email</label>
                            <input type="email" name="email" class="w-full px-4 py-3 bg-black/50 border border-emerald-700 rounded-lg focus:border-yellow-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm mb-2">Phone</label>
                            <input type="text" name="phone" class="w-full px-4 py-3 bg-black/50 border border-emerald-700 rounded-lg focus:border-yellow-500 outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm mb-2">Subject</label>
                        <input type="text" name="subject" class="w-full px-4 py-3 bg-black/50 border border-emerald-700 rounded-lg focus:border-yellow-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm mb-2">Message</label>
                        <textarea name="message" rows="5" class="w-full px-4 py-3 bg-black/50 border border-emerald-700 rounded-lg focus:border-yellow-500 outline-none"></textarea>
                    </div>
                    <button class="w-full py-3 bg-gradient-to-r from-yellow-600 to-yellow-500 text-black font-bold rounded-lg hover:from-yellow-500 hover:to-yellow-400 transition-all">Send Message</button>
                </form>
            </div>

            {{-- Contact Info + Map --}}
            <div class="space-y-6">
                <div class="grid sm:grid-cols-2 gap-4">
                    <div class="glass-morphism p-5 rounded-xl">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center"><i class="fas fa-location-dot text-black"></i></div>
                            <div>
                                <p class="text-sm text-gray-400">Address</p>
                                <p class="font-semibold">Evergreen Plaza (4th Floor), 260/B, Tejgaon I/A, Dhaka</p>
                            </div>
                        </div>
                    </div>
                    <div class="glass-morphism p-5 rounded-xl">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center"><i class="fas fa-phone text-black"></i></div>
                            <div>
                                <p class="text-sm text-gray-400">Phone</p>
                                <p class="font-semibold">+8801601662787</p>
                            </div>
                        </div>
                    </div>
                    <div class="glass-morphism p-5 rounded-xl">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center"><i class="fas fa-envelope text-black"></i></div>
                            <div>
                                <p class="text-sm text-gray-400">Email</p>
                                <p class="font-semibold">info@pojmusic.org</p>
                            </div>
                        </div>
                    </div>
                    <div class="glass-morphism p-5 rounded-xl">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center"><i class="fas fa-clock text-black"></i></div>
                            <div>
                                <p class="text-sm text-gray-400">Hours</p>
                                <p class="font-semibold">Sat–Thu: 10:00–20:00</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="glass-morphism p-3 rounded-2xl">
                    <div class="aspect-video w-full rounded-xl overflow-hidden">
                        <iframe class="w-full h-full" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                            src="https://maps.google.com/maps?q=Evergreen%20Plaza%20260%20B%20Tejgaon%20Dhaka&t=&z=13&ie=UTF8&iwloc=&output=embed"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
