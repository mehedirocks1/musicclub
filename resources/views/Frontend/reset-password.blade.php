@extends('Frontend.layout')

@section('title', 'Reset Password')

@section('content')
<div class="w-full max-w-md bg-black/50 p-8 rounded-xl shadow-lg mx-auto mt-10">
    <h2 class="text-2xl font-bold text-yellow-400 mb-4">Reset Password</h2>

    @if(session('status'))
        <p class="text-green-400 mb-4">{{ session('status') }}</p>
    @endif

    <form action="{{ route('member.password.reset') }}" method="POST" class="space-y-4">
        @csrf
        <input type="hidden" name="phone" value="{{ $phone }}">

        <div>
            <input type="password" name="password" placeholder="New Password" 
                   class="w-full p-3 rounded-lg text-black @error('password') border-red-500 @enderror" required>
            @error('password')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <input type="password" name="password_confirmation" placeholder="Confirm Password" 
                   class="w-full p-3 rounded-lg text-black" required>
        </div>

        <button type="submit" class="w-full bg-yellow-500 text-black p-3 rounded-lg font-bold">
            Reset Password
        </button>
    </form>
</div>
@endsection
