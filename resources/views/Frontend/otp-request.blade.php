@extends('Frontend.layout')

@section('title', 'Forget Password')

@section('content')
<div class="w-full max-w-md bg-black/50 p-8 rounded-xl shadow-lg mx-auto mt-10">
    <h2 class="text-2xl font-bold text-yellow-400 mb-4">Forget Password</h2>

    @if(session('status'))
        <p class="text-green-400 mb-4">{{ session('status') }}</p>
    @endif

    <form action="{{ route('member.password.otp.send') }}" method="POST" class="space-y-4">
        @csrf
        <input type="text" name="phone" placeholder="Your Mobile Number"
               class="w-full p-3 rounded-lg text-black" required>
        @error('phone') <p class="text-red-400 text-sm">{{ $message }}</p> @enderror

        <button type="submit" class="w-full bg-yellow-500 text-black p-3 rounded-lg font-bold">
            Send OTP
        </button>
    </form>
</div>
@endsection
