@extends('Frontend.layout')

@section('title', 'Verify OTP')

@section('content')
<div class="w-full max-w-md bg-black/50 p-8 rounded-xl shadow-lg mx-auto mt-10">
    <h2 class="text-2xl font-bold text-yellow-400 mb-4">Verify OTP</h2>

    <form action="{{ route('member.password.otp.verify') }}" method="POST" class="space-y-4">
        @csrf
        <input type="hidden" name="phone" value="{{ $phone }}">
        <input type="text" name="otp" placeholder="Enter OTP" class="w-full p-3 rounded-lg text-black" required>
        @error('otp') <p class="text-red-400 text-sm">{{ $message }}</p> @enderror

        <button type="submit" class="w-full bg-yellow-500 text-black p-3 rounded-lg font-bold">
            Verify OTP
        </button>
    </form>
</div>
@endsection
