@extends('frontend.layout')

@section('title', 'Payment Success - POJ Music Club')

@section('content')
<div class="flex items-center justify-center min-h-[70vh] px-4">
    <div class="text-center space-y-6 max-w-xl relative">
        <h1 class="text-5xl md:text-6xl font-bold text-yellow-400 animate-bounce">
            🎉 Congratulations! 🎉
        </h1>
        <p class="text-lg md:text-xl text-gray-300">
            Your payment has been <span class="font-semibold text-yellow-300">successful</span>.
        </p>
        <p class="text-gray-400">
            Please login to your dashboard and check your balance.
        </p>
        
        <a href="{{ route('member.dashboard') }}" class="inline-block px-8 py-4 bg-gradient-to-r from-yellow-500 to-yellow-400 text-black font-bold rounded-xl shadow-lg hover:scale-105 transform transition-all duration-300">
            Go to Dashboard
        </a>

        <canvas id="confetti-canvas" class="absolute inset-0 pointer-events-none"></canvas>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
<script>
    // Confetti function
    function launchConfetti() {
        const duration = 3 * 1000;
        const end = Date.now() + duration;

        (function frame() {
            confetti({
                particleCount: 5,
                angle: 60,
                spread: 55,
                origin: { x: 0 },
                colors: ['#fde047','#facc15','#fbbf24','#f59e0b']
            });
            confetti({
                particleCount: 5,
                angle: 120,
                spread: 55,
                origin: { x: 1 },
                colors: ['#fde047','#facc15','#fbbf24','#f59e0b']
            });

            if (Date.now() < end) {
                requestAnimationFrame(frame);
            }
        }());
    }

    // Launch confetti on page load
    window.addEventListener('DOMContentLoaded', launchConfetti);
</script>
@endpush
