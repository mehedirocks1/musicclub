@extends('frontend.layout')

@section('title', 'Packages')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10 text-white">

    @if (session('success'))
        <div class="mb-6 rounded-lg bg-emerald-900/40 border border-emerald-600/40 px-4 py-3 text-emerald-300">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-yellow-500">Packages</h1>
        <p class="text-gray-300 mt-2">Choose a plan that fits you.</p>
    </div>

    @if($packages->count())
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($packages as $package)
                <div class="rounded-2xl border border-yellow-600/20 overflow-hidden bg-black/40 backdrop-blur-xl shadow-lg hover:shadow-yellow-600/20 transition">
                    @if($package->image_path)
                        <img
                            src="{{ \Illuminate\Support\Str::startsWith($package->image_path, ['http://','https://']) ? $package->image_path : asset('storage/'.$package->image_path) }}"
                            alt="{{ $package->name }}"
                            class="h-44 w-full object-cover opacity-90"
                        >
                    @endif

                    <div class="p-5 flex flex-col gap-3">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold leading-tight text-white">
                                {{ $package->name }}
                            </h2>

                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium bg-yellow-600/20 text-yellow-400">
                                {{ ucfirst(str_replace('_',' ', $package->billing_period)) }}
                            </span>
                        </div>

                        @if($package->summary)
                            <p class="text-sm text-gray-300 line-clamp-2">
                                {{ $package->summary }}
                            </p>
                        @endif

                        <div class="mt-1">
                            @php
                                $currency = $package->currency ?: 'BDT';
                                $price    = number_format($package->price, 2);
                            @endphp
                            <div class="text-2xl font-bold text-yellow-400">
                                {{ $currency }} {{ $price }}
                            </div>
                            @if($package->billing_period !== 'one_time')
                                <div class="text-xs text-gray-400">
                                    billed {{ $package->billing_period }}
                                </div>
                            @endif
                        </div>

                        <div class="mt-2 flex flex-wrap gap-2 text-xs">
                            @if($package->sale_starts_at)
                                <span class="inline-flex items-center rounded-md bg-emerald-700/20 px-2 py-0.5 text-emerald-300 border border-emerald-700/30">
                                    Starts: {{ $package->sale_starts_at->timezone(config('app.timezone'))->format('d M Y') }}
                                </span>
                            @endif
                            @if($package->sale_ends_at)
                                <span class="inline-flex items-center rounded-md bg-rose-700/20 px-2 py-0.5 text-rose-300 border border-rose-700/30">
                                    Ends: {{ $package->sale_ends_at->timezone(config('app.timezone'))->format('d M Y') }}
                                </span>
                            @endif
                        </div>

                        <div class="mt-4 flex items-center gap-3">
                            <a href="{{ url('/packages/'.$package->slug) }}"
                               class="inline-flex items-center justify-center rounded-xl border border-yellow-600/20 px-4 py-2 text-sm font-medium text-yellow-400 hover:bg-yellow-600/10 transition">
                                Details
                            </a>

                            {{-- Buy now opens the registration form --}}
                            <a href="{{ route('frontend.packages.buy.form', $package->slug) }}"
                               class="inline-flex w-full items-center justify-center rounded-xl bg-yellow-600 px-4 py-2 text-sm font-semibold text-black hover:bg-yellow-500 transition shadow-lg">
                                Buy
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            <div class="text-white">
                {{ $packages->links() }}
            </div>
        </div>
    @else
        <div class="rounded-xl border border-yellow-600/20 bg-black/40 backdrop-blur-xl p-8 text-center text-gray-300">
            No packages available right now.
        </div>
    @endif
</div>
@endsection
