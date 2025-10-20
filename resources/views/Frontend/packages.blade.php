@extends('Frontend.layout')


@section('title', 'Packages')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">

    @if (session('success'))
        <div class="mb-6 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight">Packages</h1>
        <p class="text-gray-600 mt-2">Choose a plan that fits you.</p>
    </div>

    @if($packages->count())
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($packages as $package)
                <div class="rounded-2xl border border-gray-200 overflow-hidden bg-white shadow-sm hover:shadow-md transition">
                    @if($package->image_path)
                        <img
                            src="{{ \Illuminate\Support\Str::startsWith($package->image_path, ['http://','https://']) ? $package->image_path : asset('storage/'.$package->image_path) }}"
                            alt="{{ $package->name }}"
                            class="h-44 w-full object-cover"
                        >
                    @endif

                    <div class="p-5 flex flex-col gap-3">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold leading-tight">
                                {{ $package->name }}
                            </h2>

                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-700">
                                {{ ucfirst(str_replace('_',' ', $package->billing_period)) }}
                            </span>
                        </div>

                        @if($package->summary)
                            <p class="text-sm text-gray-600 line-clamp-2">
                                {{ $package->summary }}
                            </p>
                        @endif

                        <div class="mt-1">
                            @php
                                $currency = $package->currency ?: 'BDT';
                                $price    = number_format($package->price, 2);
                            @endphp
                            <div class="text-2xl font-bold">
                                {{ $currency }} {{ $price }}
                            </div>
                            @if($package->billing_period !== 'one_time')
                                <div class="text-xs text-gray-500">
                                    billed {{ $package->billing_period }}
                                </div>
                            @endif
                        </div>

                        <div class="mt-2 flex flex-wrap gap-2 text-xs text-gray-500">
                            @if($package->sale_starts_at)
                                <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-0.5 text-blue-700 ring-1 ring-inset ring-blue-200">
                                    Starts: {{ $package->sale_starts_at->timezone(config('app.timezone'))->format('d M Y') }}
                                </span>
                            @endif
                            @if($package->sale_ends_at)
                                <span class="inline-flex items-center rounded-md bg-rose-50 px-2 py-0.5 text-rose-700 ring-1 ring-inset ring-rose-200">
                                    Ends: {{ $package->sale_ends_at->timezone(config('app.timezone'))->format('d M Y') }}
                                </span>
                            @endif
                        </div>

                        <div class="mt-4 flex items-center gap-3">
                            <a href="{{ url('/packages/'.$package->slug) }}"
                               class="inline-flex items-center justify-center rounded-xl border border-gray-300 px-4 py-2 text-sm font-medium text-gray-800 hover:bg-gray-50">
                                Details
                            </a>

                            <form action="{{ route('frontend.packages.buy', $package->slug) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                        class="inline-flex w-full items-center justify-center rounded-xl bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800 focus:outline-none">
                                    Buy
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $packages->links() }}
        </div>
    @else
        <div class="rounded-xl border border-gray-200 bg-white p-8 text-center text-gray-600">
            No packages available right now.
        </div>
    @endif
</div>
@endsection
