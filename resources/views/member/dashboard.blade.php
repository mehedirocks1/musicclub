@extends('member.layout')

@section('title', 'Member Dashboard - POJ Music Club')

@section('content')
<div class="max-w-4xl mx-auto mt-8">

    <!-- Profile Card -->
    <div class="bg-gray-900 shadow-xl rounded-2xl overflow-hidden border border-gray-700">

        <!-- Header -->
        <div class="bg-amber-500 p-6 flex items-center gap-4">
            <!-- Profile Picture -->
            <div class="w-20 h-20 rounded-full overflow-hidden border-4 border-white flex items-center justify-center bg-white">
                @if($member->profile_pic)
                    <img src="{{ asset('storage/' . $member->profile_pic) }}" alt="Profile Picture" class="w-full h-full object-cover">
                @else
                    <span class="text-amber-500 text-2xl font-bold">{{ strtoupper(substr($member->full_name, 0, 1)) }}</span>
                @endif
            </div>
            <div>
                <h2 class="text-3xl font-bold text-white">{{ $member->full_name }}</h2>
                <p class="text-amber-200">{{ $member->membership_type }} Member</p>
            </div>
        </div>

        <!-- Member Details -->
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-800 text-gray-200">

            <div class="flex flex-col">
                <span class="text-gray-400 font-medium">Member ID</span>
                <span class="text-white font-semibold">{{ $member->id }}</span>
            </div>

            <div class="flex flex-col">
                <span class="text-gray-400 font-medium">Login Username</span>
                <span class="text-white font-semibold">{{ $member->username }}</span>
            </div>

            <div class="flex flex-col">
                <span class="text-gray-400 font-medium">Email</span>
                <span class="text-white font-semibold">{{ $member->email }}</span>
            </div>

            <div class="flex flex-col">
                <span class="text-gray-400 font-medium">Phone</span>
                <span class="text-white font-semibold">{{ $member->phone }}</span>
            </div>

            <div class="flex flex-col">
                <span class="text-gray-400 font-medium">Father's Name</span>
                <span class="text-white font-semibold">{{ $member->father_name }}</span>
            </div>

            <div class="flex flex-col">
                <span class="text-gray-400 font-medium">Mother's Name</span>
                <span class="text-white font-semibold">{{ $member->mother_name }}</span>
            </div>

            <div class="flex flex-col">
                <span class="text-gray-400 font-medium">Date of Birth</span>
                <span class="text-white font-semibold">{{ $member->dob?->format('d M, Y') }}</span>
            </div>

            <div class="flex flex-col">
                <span class="text-gray-400 font-medium">NID</span>
                <span class="text-white font-semibold">{{ $member->id_number }}</span>
            </div>

            <div class="flex flex-col">
                <span class="text-gray-400 font-medium">Gender</span>
                <span class="text-white font-semibold">{{ $member->gender }}</span>
            </div>

            <div class="flex flex-col">
                <span class="text-gray-400 font-medium">Blood Group</span>
                <span class="text-white font-semibold">{{ $member->blood_group }}</span>
            </div>

            <div class="flex flex-col">
                <span class="text-gray-400 font-medium">Profession</span>
                <span class="text-white font-semibold">{{ $member->profession }}</span>
            </div>

            <div class="flex flex-col">
                <span class="text-gray-400 font-medium">Other Expertise</span>
                <span class="text-white font-semibold">{{ $member->other_expertise ?: 'N/A' }}</span>
            </div>

            <div class="flex flex-col">
                <span class="text-gray-400 font-medium">Country</span>
                <span class="text-white font-semibold">{{ $member->country }}</span>
            </div>

            <div class="flex flex-col">
                <span class="text-gray-400 font-medium">Division</span>
                <span class="text-white font-semibold">{{ $member->division }}</span>
            </div>

            <div class="flex flex-col">
                <span class="text-gray-400 font-medium">Address</span>
                <span class="text-white font-semibold">{{ $member->address }}</span>
            </div>

            <div class="flex flex-col">
                <span class="text-gray-400 font-medium">Membership Type</span>
                <span class="text-white font-semibold">{{ $member->membership_type }}</span>
            </div>

            <!-- Corrected Balance Section -->
            @php
                use App\Models\Payments;
                use Carbon\Carbon;

                $registrationFee = 100;
                $monthlyFee = 200;
                $yearlyFee = 2400;

                $registrationDate = Carbon::parse($member->registration_date);
                $now = Carbon::now();

                $totalFeeRequired = $registrationFee;

                if ($member->membership_plan === 'monthly') {
                    $monthsToCharge = $registrationDate->copy()->startOfMonth()->diffInMonths($now->copy()->startOfMonth()) + 1;
                    $totalFeeRequired += $monthsToCharge * $monthlyFee;
                } elseif ($member->membership_plan === 'yearly') {
                    $yearsToCharge = $registrationDate->copy()->startOfYear()->diffInYears($now->copy()->startOfYear()) + 1;
                    $totalFeeRequired += $yearsToCharge * $yearlyFee;
                }

                $totalPaid = Payments::where('member_id', $member->id)
                    ->where('status', 'paid')
                    ->sum('amount');

                $balanceAmount = $totalPaid - $totalFeeRequired;

                if ($balanceAmount > 0) {
                    $balanceStatus = ['type' => 'credit', 'amount' => $balanceAmount];
                } elseif ($balanceAmount < 0) {
                    $balanceStatus = ['type' => 'due', 'amount' => abs($balanceAmount)];
                } else {
                    $balanceStatus = ['type' => 'balanced', 'amount' => 0];
                }
            @endphp

            <div class="flex flex-col">
                <span class="text-gray-400 font-medium">Balance</span>
                <span class="text-white font-semibold">
                   Total Paid {{ number_format($totalPaid, 2) }} BDT
                    @if($balanceStatus['type'] === 'credit')
                        <span class="text-green-400">(Credit: {{ number_format($balanceStatus['amount'], 2) }} BDT)</span>
                    @elseif($balanceStatus['type'] === 'due')
                        <span class="text-red-400">(Due: {{ number_format($balanceStatus['amount'], 2) }} BDT)</span>
                    @else
                        <span class="text-gray-400">(Balanced)</span>
                    @endif
                </span>
            </div>

            <div class="flex flex-col">
                <span class="text-gray-400 font-medium">Status</span>
                <span class="text-white font-semibold">{{ $member->status }}</span>
            </div>

        </div>

        <!-- Action Buttons -->
        <div class="p-6 bg-gray-900 border-t border-gray-700 flex flex-wrap gap-3">
            <a href="{{ route('member.profile') }}" class="flex-1 px-4 py-2 bg-amber-500 text-white rounded-lg text-center font-semibold hover:bg-amber-600 transition">Update Profile</a>
            <a href="{{ route('member.change-password') }}" class="flex-1 px-4 py-2 bg-amber-500 text-white rounded-lg text-center font-semibold hover:bg-amber-600 transition">Change Password</a>
            <a href="{{ route('member.pay-fee') }}" class="flex-1 px-4 py-2 bg-amber-500 text-white rounded-lg text-center font-semibold hover:bg-amber-600 transition">Pay Monthly Fee</a>
            <a href="{{ route('member.check-payments') }}" class="flex-1 px-4 py-2 bg-amber-500 text-white rounded-lg text-center font-semibold hover:bg-amber-600 transition">Check Payments</a>
        </div>

    </div>

</div>
@endsection
