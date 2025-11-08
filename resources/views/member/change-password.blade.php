@extends('member.layout')

@section('title', 'Change Password - POJ Music Club')

@section('content')
<div class="max-w-3xl mx-auto mt-8 p-4">

    <h2 class="text-2xl font-bold mb-6 text-amber-400">Change Password</h2>

    {{-- Form --}}
    <form action="{{ route('member.change-password.update') }}" method="POST" class="bg-gray-800 shadow rounded-lg p-6 space-y-4 relative">
        @csrf

        {{-- Current Password --}}
        <div class="relative">
            <label class="block text-gray-300 font-medium mb-1" for="current_password">Current Password</label>
            <input id="current_password" name="current_password" type="password" required
                   class="w-full border border-gray-600 rounded px-3 py-2 bg-gray-700 text-white pr-10"
                   autocomplete="current-password">
            <button type="button" onclick="togglePassword('current_password', this)"
                    class="absolute right-2 top-9 text-gray-400 hover:text-gray-200">
                <i class="fas fa-eye"></i>
            </button>
            @error('current_password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- New Password --}}
        <div class="relative">
            <label class="block text-gray-300 font-medium mb-1" for="password">New Password</label>
            <input id="password" name="password" type="password" required
                   class="w-full border border-gray-600 rounded px-3 py-2 bg-gray-700 text-white pr-10"
                   autocomplete="new-password">
            <button type="button" onclick="togglePassword('password', this)"
                    class="absolute right-2 top-9 text-gray-400 hover:text-gray-200">
                <i class="fas fa-eye"></i>
            </button>
            @error('password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Confirm New Password --}}
        <div class="relative">
            <label class="block text-gray-300 font-medium mb-1" for="password_confirmation">Confirm New Password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required
                   class="w-full border border-gray-600 rounded px-3 py-2 bg-gray-700 text-white pr-10"
                   autocomplete="new-password">
            <button type="button" onclick="togglePassword('password_confirmation', this)"
                    class="absolute right-2 top-9 text-gray-400 hover:text-gray-200">
                <i class="fas fa-eye"></i>
            </button>
            @error('password_confirmation')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Submit Button --}}
        <div class="flex justify-end mt-4">
            <button type="submit"
                    class="px-4 py-2 bg-amber-500 text-white rounded hover:bg-amber-600 transition font-medium">
                Update Password
            </button>
        </div>
    </form>
</div>

{{-- Toast Notifications --}}
@if(session('success') || session('error'))
    <div id="toast-notification"
         class="fixed top-5 right-5 p-4 rounded shadow text-white
         {{ session('success') ? 'bg-green-600' : 'bg-red-600' }} transition-opacity duration-500">
        {{ session('success') ?? session('error') }}
    </div>
@endif

{{-- Scripts --}}
<script>
    // Toggle password visibility
    function togglePassword(id, btn) {
        const input = document.getElementById(id);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // Auto-hide toast
    const toast = document.getElementById('toast-notification');
    if (toast) {
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 500);
        }, 4000);
    }
</script>
@endsection
