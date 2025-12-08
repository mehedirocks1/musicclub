@extends('Frontend.layout')


@section('title', 'Forget Password')

@section('content')
<div class="w-full max-w-md bg-black/50 p-8 rounded-xl shadow-lg">
    <h2 class="text-2xl font-bold text-yellow-400 mb-4">Forget Password</h2>
    <form action="{{ route('member.password.otp.send') }}" method="POST" class="space-y-4">
        @csrf
        <input type="text" name="mobile" placeholder="Your Mobile Number"
               class="w-full p-3 rounded-lg text-black" required>
        <button type="submit" class="w-full bg-yellow-500 text-black p-3 rounded-lg font-bold">Send OTP</button>
    </form>
</div>
@endsection
