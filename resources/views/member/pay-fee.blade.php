@extends('member.layout')

@section('title', 'Pay Membership Fee - POJ Music Club')

@section('content')
<div class="max-w-3xl mx-auto mt-8 p-4">

    <h2 class="text-2xl font-bold mb-6 text-amber-400">Pay Membership Fee</h2>

    {{-- Current Balance --}}
    <div class="mb-6 p-4 bg-gray-700 text-gray-100 rounded shadow">
        <p class="font-medium">Current Balance: <span class="font-bold">{{ number_format($member->balance, 2) }} BDT</span></p>
        <p class="text-gray-300 text-sm">This is the total paid amount or remaining balance for your membership.</p>
    </div>

    {{-- Success / Error messages --}}
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-700 text-green-100 rounded shadow">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-3 bg-red-700 text-red-100 rounded shadow">
            {{ session('error') }}
        </div>
    @endif

    {{-- Payment Form --}}
    <form action="{{ url('sslcommerz/m-pay-fee') }}" method="POST" class="bg-gray-800 shadow rounded-lg p-6 space-y-4">
        @csrf

        {{-- Membership Type --}}
        <div>
            <p class="text-gray-200 font-medium">
                Your Membership Type: <span class="font-bold">{{ ucfirst($member->membership_plan) }}</span>
            </p>
        </div>

        {{-- Monthly members: select months --}}
        @if($member->membership_plan === 'monthly')
            <div>
                <label for="months" class="block text-gray-200 font-medium mb-1">Select Number of Months</label>
                <select name="months" id="months"
                        class="w-full border border-gray-600 rounded px-3 py-2 bg-gray-700 text-white" required>
                    @for($i=1; $i<=12; $i++)
                        <option value="{{ $i }}">{{ $i }} month{{ $i > 1 ? 's' : '' }} ({{ $i * 200 }} BDT)</option>
                    @endfor
                </select>
            </div>
        @endif

        {{-- Display amount --}}
        <div>
            <label class="block text-gray-200 font-medium mb-1">Amount to Pay (BDT)</label>
            <div class="w-full border border-gray-600 rounded px-3 py-2 bg-gray-600 text-white cursor-not-allowed" id="amount-display">
                {{ $member->membership_plan === 'yearly' ? 2400 : 200 }} BDT
            </div>
        </div>

        {{-- Hidden inputs --}}
        <input type="hidden" name="member_id" value="{{ $member->id }}">
        <input type="hidden" name="membership_plan" value="{{ $member->membership_plan }}">
        <input type="hidden" name="amount" id="amount" value="{{ $member->membership_plan === 'yearly' ? 2400 : 200 }}">
        <input type="hidden" name="tran_id" value="INV-{{ \Illuminate\Support\Str::uuid() }}">

        {{-- Submit Button --}}
        <div class="flex justify-end mt-4">
            <button type="submit"
                    class="px-6 py-2 bg-amber-500 text-white rounded hover:bg-amber-600 transition font-medium">
                Pay Fee via SSLCOMMERZ
            </button>
        </div>
    </form>
</div>

{{-- JS to update amount for monthly members --}}
@if($member->membership_plan === 'monthly')
<script>
    const monthsSelect = document.getElementById('months');
    const amountInput = document.getElementById('amount');
    const amountDisplay = document.getElementById('amount-display');

    monthsSelect.addEventListener('change', function() {
        const months = parseInt(this.value);
        const total = months * 200; // 200 BDT per month
        amountInput.value = total;           // hidden input for backend
        amountDisplay.textContent = total + ' BDT'; // visible to user
    });
</script>
@endif
@endsection
