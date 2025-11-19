@extends('frontend.layout')

@section('title', $package->name)

@section('content')
@php
    $tz = config('app.timezone', 'Asia/Dhaka');
    $currency = $package->currency ?: 'BDT';
    $price = number_format((float) $package->price, 2);

    // --- Clean Summary ---
    $summary = $package->summary;
    if (is_array($summary)) {
        $summary = implode(', ', array_filter($summary, fn($v) => is_scalar($v)));
    }

    // --- Clean Description + Remove Extra Blank Lines ---
    $description = $package->description;
    if (is_array($description)) {
        $description = implode("\n", array_filter($description, fn($v) => is_scalar($v)));
    }
    $description = preg_replace("/\n+/", "\n", trim($description));

    // --- Clean Audience ---
    $audience = $package->target_audience;
    if (is_array($audience)) {
        $audience = implode(', ', array_filter($audience, fn($v) => is_scalar($v)));
    }
@endphp

<div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 py-10">

    {{-- Back --}}
    <a href="{{ route('frontend.packages.index') }}"
       class="inline-flex items-center text-sm text-gray-400 hover:text-white mb-6">
        ← Back to Packages
    </a>

    <div class="bg-[#111] rounded-2xl border border-gray-800 shadow-lg overflow-hidden">

        {{-- Promo video OR image --}}
        @if($package->promo_video_url)
            <div class="aspect-video w-full">
                <iframe src="{{ $package->promo_video_url }}" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
            </div>
        @elseif($package->image_path)
            <img
                src="{{ \Illuminate\Support\Str::startsWith($package->image_path, ['http://','https://']) ? $package->image_path : asset('storage/'.$package->image_path) }}"
                alt="{{ $package->name }}"
                class="w-full h-72 object-cover"
            >
        @endif

        <div class="p-6 flex flex-col gap-6">

            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h1 class="text-3xl font-bold text-white">{{ $package->name }}</h1>

                    <div class="mt-2 flex flex-wrap gap-2 text-xs">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 bg-gray-800 text-gray-300">
                            {{ ucfirst(str_replace('_',' ', $package->billing_period)) }}
                        </span>

                        @if($package->sale_starts_at)
                            <span class="inline-flex items-center rounded-md bg-blue-900/40 px-2 py-0.5 text-blue-300 ring-1 ring-blue-700/40">
                                Starts: {{ $package->sale_starts_at->timezone($tz)->format('d M Y') }}
                            </span>
                        @endif

                        @if($package->sale_ends_at)
                            <span class="inline-flex items-center rounded-md bg-rose-900/40 px-2 py-0.5 text-rose-300 ring-1 ring-rose-700/40">
                                Ends: {{ $package->sale_ends_at->timezone($tz)->format('d M Y') }}
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Price block --}}
                <div class="text-right">
                    <div class="text-2xl font-semibold text-gray-100">{{ $currency }} {{ $price }}</div>

                    @if($package->billing_period !== 'one_time')
                        <div class="text-xs text-gray-500">billed {{ $package->billing_period }}</div>
                    @endif

                    @unless($canBuy)
                        <div class="mt-1 text-xs text-amber-400">Not available</div>
                    @endunless
                </div>
            </div>

            {{-- Summary --}}
            @if(!empty($summary))
                <p class="text-gray-300 text-base leading-relaxed">{{ $summary }}</p>
            @endif

            {{-- Description --}}
            @if(!empty($description))
                <div class="prose prose-invert max-w-none text-gray-300 leading-relaxed">
                    {!! nl2br(e($description)) !!}
                </div>
            @endif

            {{-- Features --}}
            @if(is_array($package->features) && count($package->features))
                <div>
                    <h2 class="text-lg font-semibold mb-2 text-white">Features</h2>
                    <ul class="list-disc list-inside text-gray-300 space-y-1">
                        @foreach($package->features as $feature)
                            <li>{{ is_scalar($feature) ? $feature : '' }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Audience --}}
            @if(!empty($audience))
                <div>
                    <h2 class="text-lg font-semibold mb-2 text-white">Target Audience</h2>
                    <p class="text-gray-300">{{ $audience }}</p>
                </div>
            @endif

            {{-- Buy button --}}
            <div class="pt-4">
                <form action="{{ route('frontend.packages.buy', $package->slug) }}" method="POST">
                    @csrf
                    <button type="submit"
                            @class([
                                'inline-flex items-center justify-center rounded-xl px-6 py-3 text-sm font-semibold focus:outline-none transition',
                                'bg-green-600 hover:bg-green-500 text-white' => $canBuy,
                                'bg-gray-700 text-gray-500 cursor-not-allowed' => !$canBuy,
                            ])
                            @if(!$canBuy) disabled @endif>
                        {{ $canBuy ? 'Buy This Package' : 'Unavailable' }}
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
