@extends('member.layout')

@section('title', 'Member Login - POJ Music Club')

@section('content')
{{-- Ensure full screen height and add vertical padding for small screens --}}
<div class="flex items-center justify-center min-h-screen bg-gradient-to-b from-gray-900 via-gray-800 to-gray-900 p-4 sm:p-8">
    
    {{-- 🎯 Key Responsive Strategy: 
         - max-w-lg (32rem) on mobile/default.
         - sm:max-w-xl (36rem) on small tablets.
         - md:max-w-2xl (42rem) on tablets/small desktops.
         - lg:max-w-3xl (48rem) on standard desktops. 
         - xl:max-w-4xl (56rem) on large monitors. 
         This provides a smooth, progressive increase up to a comfortable max width.
    --}}
    <div class="w-full max-w-lg sm:max-w-xl md:max-w-2xl lg:max-w-3xl xl:max-w-4xl 
                p-6 sm:p-10 bg-gray-800 rounded-2xl shadow-2xl glass-morphism 
                transition-all duration-300">
        
        {{-- Responsive Heading Size --}}
        <h2 class="text-3xl sm:text-4xl font-bold text-yellow-400 mb-8 sm:mb-10 text-center tracking-wide">Member Login</h2>

        {{-- Validation Errors (Keep styles) --}}
        @if ($errors->any())
            <div class="mb-6 text-red-500 text-sm bg-red-900/30 p-4 rounded-lg">
                <ul class="list-disc pl-6">
                    @foreach ($errors->all() as $error)
                        <li>⚠️ {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Login Form --}}
        <form action="{{ route('member.login') }}" method="POST" class="space-y-6 sm:space-y-8">
            @csrf

            <div>
                <label for="email" class="block text-yellow-300 mb-2 font-medium text-base sm:text-lg">Email</label>
                {{-- Responsive input padding and text size --}}
                <input type="email" name="email" id="email" required autofocus
                        class="w-full p-3 sm:p-4 text-base sm:text-lg rounded-lg border border-yellow-500 bg-gray-900 text-white focus:ring-2 focus:ring-yellow-400 outline-none transition-all duration-200"
                        placeholder="mehedi@mehedihasan.me">
            </div>

            <div>
                <label for="password" class="block text-yellow-300 mb-2 font-medium text-base sm:text-lg">Password</label>
                {{-- Responsive input padding and text size --}}
                <input type="password" name="password" id="password" required
                        class="w-full p-3 sm:p-4 text-base sm:text-lg rounded-lg border border-yellow-500 bg-gray-900 text-white focus:ring-2 focus:ring-yellow-400 outline-none transition-all duration-200"
                        placeholder="••••••••">
            </div>

            <div class="flex items-center justify-between text-sm sm:text-base text-yellow-300">
                <label class="flex items-center gap-2">
                    {{-- Standard checkbox size, looks fine with responsive text --}}
                    <input type="checkbox" name="remember" class="rounded text-yellow-500"> 
                    Remember Me
                </label>
                <a href="#" class="hover:underline text-yellow-400 font-medium">Forgot Password?</a>
            </div>

            {{-- Responsive button padding and text size --}}
            <button type="submit"
                    class="w-full py-3 sm:py-4 bg-yellow-500 hover:bg-yellow-400 text-black font-bold rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl text-lg sm:text-xl mt-6">
                Login
            </button>
        </form>

        {{-- Responsive link text size --}}
        <p class="mt-8 text-center text-yellow-300 text-base sm:text-lg">
            Don't have an account? 
            <a href="{{ route('register') }}" class="text-yellow-400 font-bold hover:underline">Register Here</a>
        </p>
    </div>
</div>
@endsection