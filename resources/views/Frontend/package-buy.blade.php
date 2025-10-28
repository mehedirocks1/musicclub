@extends('frontend.layout')

@section('title', 'Buy: ' . $package->name)

@section('content')
<div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-10">

    <a href="{{ route('frontend.packages.show', $package->slug) }}" class="text-sm text-gray-600 hover:text-gray-900">
        ← Back
    </a>

    <div class="mt-6 bg-white dark:bg-neutral-900 shadow-sm rounded-2xl border border-gray-200 dark:border-neutral-800 overflow-hidden">
        {{-- Header --}}
        <div class="px-6 py-4 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $package->name }}</h1>
                <p class="text-gray-600 dark:text-neutral-300 text-sm">
                    {{ ucfirst(str_replace('_',' ', $package->billing_period)) }} —
                    <strong>{{ $package->currency ?: 'BDT' }} {{ number_format($package->price, 2) }}</strong>
                </p>
            </div>
            @if ($package->image_path)
                <img
                    src="{{ \Illuminate\Support\Str::startsWith($package->image_path, ['http://','https://']) ? $package->image_path : asset('storage/'.$package->image_path) }}"
                    class="w-24 h-16 object-cover rounded-md border border-gray-200 dark:border-neutral-700"
                    alt="{{ $package->name }}"
                >
            @endif
        </div>

        @if ($errors->any())
            <div class="mx-6 mb-4 rounded-lg border border-rose-300/60 bg-rose-50 text-rose-800 dark:bg-rose-950/40 dark:text-rose-200 dark:border-rose-900 p-3">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('frontend.packages.buy', $package->slug) }}" method="POST" enctype="multipart/form-data" class="space-y-6 px-6 pb-8">
            @csrf

            <h2 class="text-lg font-semibold pb-2 border-b border-gray-200 dark:border-neutral-800 text-gray-900 dark:text-white">
                Registration Information
            </h2>

            @php
                // High-contrast, accessible defaults
                $baseField = 'w-full rounded-xl border bg-white text-gray-900 placeholder:text-gray-400
                              focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900/20
                              dark:bg-neutral-800 dark:text-neutral-50 dark:placeholder-neutral-400
                              dark:border-neutral-700 dark:focus:ring-neutral-500/30 dark:focus:border-neutral-500/30 px-3 py-2';
                $inputCls   = $baseField;
                $selectCls  = $baseField . ' pr-8';
                $textareaCls= $baseField;
                $labelCls   = 'block text-sm font-medium mb-1 text-gray-700 dark:text-neutral-300';
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="{{ $labelCls }}">Full Name *</label>
                    <input type="text" name="full_name" value="{{ old('full_name') }}" class="{{ $inputCls }}" required>
                </div>
                <div>
                    <label class="{{ $labelCls }}">Name (Bangla)</label>
                    <input type="text" name="name_bn" value="{{ old('name_bn') }}" class="{{ $inputCls }}">
                </div>

                <div>
                    <label class="{{ $labelCls }}">Username *</label>
                    <input type="text" name="username" value="{{ old('username') }}" class="{{ $inputCls }}" required>
                </div>
                <div>
                    <label class="{{ $labelCls }}">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="{{ $inputCls }}">
                </div>

                <div>
                    <label class="{{ $labelCls }}">Phone *</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="{{ $inputCls }}" required>
                </div>
                <div>
                    <label class="{{ $labelCls }}">Date of Birth</label>
                    <input type="date" name="dob" value="{{ old('dob') }}" class="{{ $inputCls }}">
                </div>

                <div>
                    <label class="{{ $labelCls }}">Gender</label>
                    <select name="gender" class="{{ $selectCls }}">
                        <option value="">Select</option>
                        <option value="male"   @selected(old('gender')==='male')>Male</option>
                        <option value="female" @selected(old('gender')==='female')>Female</option>
                        <option value="other"  @selected(old('gender')==='other')>Other</option>
                    </select>
                </div>
                <div>
                    <label class="{{ $labelCls }}">Blood Group</label>
                    <input type="text" name="blood_group" value="{{ old('blood_group') }}" placeholder="A+, O-, ..." class="{{ $inputCls }}">
                </div>

                <div class="sm:col-span-2">
                    <label class="{{ $labelCls }}">ID Number (NID/Passport/Student ID)</label>
                    <input type="text" name="id_number" value="{{ old('id_number') }}" class="{{ $inputCls }}">
                </div>

                <div>
                    <label class="{{ $labelCls }}">Education</label>
                    <input type="text" name="education" value="{{ old('education') }}" class="{{ $inputCls }}">
                </div>
                <div>
                    <label class="{{ $labelCls }}">Profession</label>
                    <input type="text" name="profession" value="{{ old('profession') }}" class="{{ $inputCls }}">
                </div>

                <div class="sm:col-span-2">
                    <label class="{{ $labelCls }}">Other Expertise</label>
                    <input type="text" name="expertise" value="{{ old('expertise') }}" class="{{ $inputCls }}">
                </div>

                <div>
                    <label class="{{ $labelCls }}">Country</label>
                    <input type="text" name="country" value="{{ old('country','Bangladesh') }}" class="{{ $inputCls }}">
                </div>
                <div>
                    <label class="{{ $labelCls }}">Division</label>
                    <input type="text" name="division" value="{{ old('division') }}" class="{{ $inputCls }}">
                </div>
                <div>
                    <label class="{{ $labelCls }}">District</label>
                    <input type="text" name="district" value="{{ old('district') }}" class="{{ $inputCls }}">
                </div>

                <div class="sm:col-span-2">
                    <label class="{{ $labelCls }}">Address</label>
                    <textarea name="address" rows="2" class="{{ $textareaCls }}">{{ old('address') }}</textarea>
                </div>

                <div class="sm:col-span-2 flex items-center gap-2">
                    <input id="is_student" type="checkbox" name="is_student" value="1" @checked(old('is_student'))
                           class="h-4 w-4 rounded border-gray-300 text-gray-900 focus:ring-2 focus:ring-gray-900/20 dark:border-neutral-700 dark:bg-neutral-800">
                    <label for="is_student" class="text-sm text-gray-700 dark:text-neutral-300">Student</label>
                </div>

                <div class="sm:col-span-2">
                    <label class="{{ $labelCls }}">Profile Picture</label>
                    <input type="file" name="profile_picture" accept="image/*"
                           class="block w-full text-sm text-gray-700 dark:text-neutral-200
                                  file:mr-3 file:rounded-lg file:border file:border-gray-300 file:bg-white
                                  file:text-gray-900 file:px-4 file:py-2 file:hover:bg-gray-50
                                  dark:file:bg-neutral-800 dark:file:text-neutral-100 dark:file:border-neutral-700">
                </div>

                

            <div class="pt-2">
                <button type="submit"
                        class="inline-flex items-center justify-center rounded-xl bg-gray-900 px-6 py-3 text-sm font-semibold text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-900/20 dark:bg-white dark:text-neutral-900 dark:hover:bg-neutral-200">
                    Continue to Payment
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
