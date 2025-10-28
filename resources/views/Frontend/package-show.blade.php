@extends('frontend.layout')

@section('title', $package->name)

@section('content')
@php
    $tz = config('app.timezone', 'Asia/Dhaka');
    $currency = $package->currency ?: 'BDT';
    $price = number_format((float) $package->price, 2);

    // Normalize fields that might unexpectedly be arrays (e.g., if saved as JSON)
    $summary = $package->summary;
    if (is_array($summary)) {
        // Join nested arrays safely, flatten scalars only
        $summary = implode(', ', array_map(
            fn ($v) => is_scalar($v) ? (string)$v : '',
            array_filter($summary, fn ($v) => is_scalar($v))
        ));
    }

    $description = $package->description;
    if (is_array($description)) {
        $description = implode("\n", array_map(
            fn ($v) => is_scalar($v) ? (string)$v : '',
            array_filter($description, fn ($v) => is_scalar($v))
        ));
    }

    $audience = $package->target_audience;
    if (is_array($audience)) {
        $audience = implode(', ', array_map(
            fn ($v) => is_scalar($v) ? (string)$v : '',
            array_filter($audience, fn ($v) => is_scalar($v))
        ));
    }
@endphp

<div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 py-10">

    {{-- Back --}}
    <a href="{{ route('frontend.packages.index') }}"
       class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 mb-6">
        ← Back to Packages
    </a>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

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

        <div class="p-6 flex flex-col gap-5">

            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $package->name }}</h1>
                    <div class="mt-2 flex flex-wrap gap-2 text-xs">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 bg-gray-100 text-gray-700">
                            {{ ucfirst(str_replace('_',' ', $package->billing_period)) }}
                        </span>

                        {{-- Sale window badges --}}
                        @if($package->sale_starts_at)
                            <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-0.5 text-blue-700 ring-1 ring-inset ring-blue-200">
                                Starts: {{ $package->sale_starts_at->timezone($tz)->format('d M Y') }}
                            </span>
                        @endif
                        @if($package->sale_ends_at)
                            <span class="inline-flex items-center rounded-md bg-rose-50 px-2 py-0.5 text-rose-700 ring-1 ring-inset ring-rose-200">
                                Ends: {{ $package->sale_ends_at->timezone($tz)->format('d M Y') }}
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Price block --}}
                <div class="text-right">
                    <div class="text-2xl font-semibold text-gray-900">{{ $currency }} {{ $price }}</div>
                    @if($package->billing_period !== 'one_time')
                        <div class="text-xs text-gray-500">billed {{ $package->billing_period }}</div>
                    @endif
                    @unless($canBuy)
                        <div class="mt-1 text-xs text-amber-600">Not currently available to purchase</div>
                    @endunless
                </div>
            </div>

            {{-- Summary --}}
            @if(!empty($summary))
                <p class="text-gray-700 text-base leading-relaxed">{{ $summary }}</p>
            @endif

            {{-- Description --}}
            @if(!empty($description))
                <div class="prose max-w-none text-gray-800 leading-relaxed">
                    {!! nl2br(e($description)) !!}
                </div>
            @endif

            {{-- Features --}}
            @if(is_array($package->features) && count($package->features))
                <div>
                    <h2 class="text-lg font-semibold mb-2">Features</h2>
                    <ul class="list-disc list-inside text-gray-700 space-y-1">
                        @foreach($package->features as $feature)
                            <li>{{ is_scalar($feature) ? $feature : '' }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Target audience --}}
            @if(!empty($audience))
                <div>
                    <h2 class="text-lg font-semibold mb-2">Target Audience</h2>
                    <p class="text-gray-700">{{ $audience }}</p>
                </div>
            @endif

            {{-- Buy button --}}
            <div class="pt-2">
                <form action="{{ route('frontend.packages.buy', $package->slug) }}" method="POST">
                    @csrf
                    <button type="submit"
                            @class([
                                'inline-flex items-center justify-center rounded-xl px-6 py-3 text-sm font-semibold focus:outline-none',
                                'bg-gray-900 text-white hover:bg-gray-800' => $canBuy,
                                'bg-gray-300 text-gray-600 cursor-not-allowed' => !$canBuy,
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
