@extends('frontend.layout')

@section('title', 'Register - POJ Music Club')

@section('content')
<div class="relative z-10">
    {{-- Container width expanded to max-w-4xl (was max-w-3xl) --}}
    <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="glass-morphism rounded-2xl p-8 shadow-xl border border-yellow-600/30">
            <h1 class="text-3xl font-extrabold text-gradient-yellow text-center mb-8">Join POJ Music Club</h1>

            {{-- Success flash --}}
            @if(session('success'))
                <div class="mb-6 rounded-lg border border-emerald-500/30 bg-emerald-500/10 p-4 text-emerald-300">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Validation errors --}}
            @if ($errors->any())
                <div class="mb-6 rounded-lg border border-red-500/30 bg-red-500/10 p-4">
                    <ul class="list-disc list-inside text-red-300 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Personal Information --}}
                    <div>
                        <label class="block text-sm text-gray-300 mb-1">Full Name <span class="text-yellow-500">*</span></label>
                        <input type="text" name="full_name" value="{{ old('full_name') }}" required
                               class="w-full rounded-lg bg-black/40 border border-yellow-600/30 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-300 mb-1">Name (Bangla)</label>
                        <input type="text" name="name_bn" value="{{ old('name_bn') }}"
                               class="w-full rounded-lg bg-black/40 border border-yellow-600/30 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-300 mb-1">Father's Name</label>
                        <input type="text" name="father_name" value="{{ old('father_name') }}"
                               class="w-full rounded-lg bg-black/40 border border-yellow-600/30 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-300 mb-1">Mother's Name</label>
                        <input type="text" name="mother_name" value="{{ old('mother_name') }}"
                               class="w-full rounded-lg bg-black/40 border border-yellow-600/30 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-300 mb-1">Username <span class="text-yellow-500">*</span></label>
                        <input type="text" name="username" value="{{ old('username') }}" required
                               class="w-full rounded-lg bg-black/40 border border-yellow-600/30 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-300 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="w-full rounded-lg bg-black/40 border border-yellow-600/30 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-300 mb-1">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                               class="w-full rounded-lg bg-black/40 border border-yellow-600/30 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-300 mb-1">Date of Birth</label>
                        <input type="date" name="dob" value="{{ old('dob') }}"
                               class="w-full rounded-lg bg-black/40 border border-yellow-600/30 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-300 mb-1">Gender</label>
                        <select name="gender"
                                class="w-full rounded-lg bg-black/40 border border-yellow-600/30 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                            <option value="">Select</option>
                            @foreach (\Modules\Members\Models\Member::GENDERS as $g)
                                <option value="{{ $g }}" @selected(old('gender')===$g)>{{ $g }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-300 mb-1">Blood Group</label>
                        <select name="blood_group"
                                class="w-full rounded-lg bg-black/40 border border-yellow-600/30 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                            <option value="">Select</option>
                            @foreach (\Modules\Members\Models\Member::BLOOD_GROUPS as $bg)
                                <option value="{{ $bg }}" @selected(old('blood_group')===$bg)>{{ $bg }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm text-gray-300 mb-1">ID Number (NID/Passport/Student ID)</label>
                        <input type="text" name="id_number" value="{{ old('id_number') }}"
                               class="w-full rounded-lg bg-black/40 border border-yellow-600/30 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-300 mb-1">Education</label>
                        <input type="text" name="education_qualification" value="{{ old('education_qualification') }}"
                               class="w-full rounded-lg bg-black/40 border border-yellow-600/30 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-300 mb-1">Profession</label>
                        <input type="text" name="profession" value="{{ old('profession') }}"
                               class="w-full rounded-lg bg-black/40 border border-yellow-600/30 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm text-gray-300 mb-1">Other Expertise</label>
                        <textarea name="other_expertise" rows="3"
                                  class="w-full rounded-lg bg-black/40 border border-yellow-600/30 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-yellow-500">{{ old('other_expertise') }}</textarea>
                    </div>

                    {{-- Address Fields --}}
                    <div>
                        <label class="block text-sm text-gray-300 mb-1">Country</label>
                        <input type="text" name="country" value="{{ old('country','Bangladesh') }}"
                               class="w-full rounded-lg bg-black/40 border border-yellow-600/30 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-300 mb-1">Division</label>
                        <input type="text" name="division" value="{{ old('division') }}"
                               class="w-full rounded-lg bg-black/40 border border-yellow-600/30 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-300 mb-1">District</label>
                        <input type="text" name="district" value="{{ old('district') }}"
                               class="w-full rounded-lg bg-black/40 border border-yellow-600/30 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm text-gray-300 mb-1">Address</label>
                        <textarea name="address" rows="3"
                                  class="w-full rounded-lg bg-black/40 border border-yellow-600/30 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-yellow-500">{{ old('address') }}</textarea>
                    </div>

                    {{-- Membership Type & Picture --}}
                    <div>
                        <label class="block text-sm text-gray-300 mb-1">Membership Type <span class="text-yellow-500">*</span></label>
                        <select name="membership_type" required
                                class="w-full rounded-lg bg-black/40 border border-yellow-600/30 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                            @foreach (\Modules\Members\Models\Member::MEMBERSHIP_TYPES as $type)
                                <option value="{{ $type }}" @selected(old('membership_type')===$type)>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-300 mb-1">Profile Picture</label>
                        <input type="file" name="profile_pic" accept="image/*"
                               class="w-full rounded-lg bg-black/40 border border-yellow-600/30 px-4 py-2 file:mr-4 file:rounded-lg file:border-0 file:bg-yellow-500 file:text-black file:font-semibold hover:file:bg-yellow-400">
                    </div>

                    {{-- Password Fields --}}
                    <div>
                        <label class="block text-sm text-gray-300 mb-1">Password <span class="text-yellow-500">*</span></label>
                        <div class="relative">
                            <input type="password" name="password" id="password" required
                                   class="w-full rounded-lg bg-black/40 border border-yellow-600/30 px-4 py-3 pr-10 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                            <button type="button" id="togglePassword"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-yellow-400">
                                <svg class="h-5 w-5 eye-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg class="h-5 w-5 eye-slash-icon hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7 1.274-4.057 5.064-7 9.542-7 .847 0 1.67.127 2.45.364m1.09 1.091A9.95 9.95 0 0112 5c-4.478 0-8.268 2.943-9.542 7a10.05 10.05 0 003.628 4.675M19.542 12c-.274-.82-.62-1.605-1.028-2.338m-3.18-3.18A9.95 9.95 0 0112 5c4.478 0 8.268 2.943 9.542 7-.274.82-.62 1.605-1.028 2.338m-3.18 3.18A9.95 9.95 0 0012 19c-.847 0-1.67-.127-2.45-.364M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-300 mb-1">Confirm Password <span class="text-yellow-500">*</span></label>
                           <div class="relative">
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                   class="w-full rounded-lg bg-black/40 border border-yellow-600/30 px-4 py-3 pr-10 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                            <button type="button" id="togglePasswordConfirmation"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-yellow-400">
                                <svg class="h-5 w-5 eye-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg class="h-5 w-5 eye-slash-icon hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7 1.274-4.057 5.064-7 9.542-7 .847 0 1.67.127 2.45.364m1.09 1.091A9.95 9.95 0 0112 5c-4.478 0-8.268 2.943-9.542 7a10.05 10.05 0 003.628 4.675M19.542 12c-.274-.82-.62-1.605-1.028-2.338m-3.18-3.18A9.95 9.95 0 0112 5c4.478 0 8.268 2.943 9.542 7-.274.82-.62 1.605-1.028 2.338m-3.18 3.18A9.95 9.95 0 0012 19c-.847 0-1.67-.127-2.45-.364M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- NEW: Membership Plan & Fee Breakdown --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm text-gray-300 mb-2">Membership Plan <span class="text-yellow-500">*</span></label>
                        
                        {{-- Hidden input for Registration Fee (৳100) --}}
                        <input type="hidden" name="registration_fee" id="registration-fee" value="100">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
                            <label class="flex items-center gap-3 rounded-lg border border-yellow-600/30 bg-black/40 p-3 cursor-pointer hover:border-yellow-400/60">
                                <input type="radio" name="membership_plan" value="monthly" required
                                       @checked(old('membership_plan')==='monthly') class="accent-yellow-500" data-fee="200">
                                <span class="text-yellow-400 font-semibold">Monthly Plan</span>
                                <span class="ml-auto text-gray-300">৳200 / month</span>
                            </label>

                            <label class="flex items-center gap-3 rounded-lg border border-yellow-600/30 bg-black/40 p-3 cursor-pointer hover:border-yellow-400/60">
                                <input type="radio" name="membership_plan" value="yearly" required
                                       @checked(old('membership_plan')==='yearly') class="accent-yellow-500" data-fee="2400">
                                <span class="text-yellow-400 font-semibold">Yearly Plan</span>
                                <span class="ml-auto text-gray-300">৳2,400 / year</span>
                            </label>
                        </div>
                        
                        {{-- Fee Summary Section --}}
                        <div class="rounded-lg border border-yellow-600/30 bg-black/30 p-4 text-sm text-gray-300">
                            <div class="flex items-center justify-between mb-1">
                                <span>One-time Registration Fee</span>
                                <span class="font-bold text-gray-300">৳100</span>
                            </div>
                            <div class="flex items-center justify-between border-b border-yellow-600/20 pb-2 mb-2">
                                <span>Initial Membership Fee</span>
                                <span id="membership-fee" class="font-bold text-yellow-400">৳0</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-base font-semibold">Total Payable Amount</span>
                                <span id="payable-amount" class="text-lg font-extrabold text-yellow-300">৳100</span>
                            </div>
                            <p class="text-xs text-gray-400 mt-2">The total amount will be processed via SSLCommerz. Upon success, the initial membership fee will be applied to your account.</p>
                        </div>

                    </div>
                </div>

                {{-- Agreement checkbox (required) --}}
                <div class="pt-4">
                    <label class="inline-flex items-start gap-3 text-sm text-gray-300">
                        <input type="checkbox" name="agree_terms" id="agree_terms" required class="mt-1 accent-yellow-500">
                        <span>
                            I have read and agree to the
                            <a href="{{ url('/terms') }}" target="_blank" class="text-yellow-400 underline">Terms &amp; Conditions</a>,
                            <a href="{{ url('/privacy') }}" target="_blank" class="text-yellow-400 underline">Privacy Policy</a>, and
                            <a href="{{ url('/refund-policy') }}" target="_blank" class="text-yellow-400 underline">Return / Refund / Cancellation Policy</a>.
                        </span>
                    </label>
                    @error('agree_terms')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4 flex items-center justify-between">
                    <a href="{{ route('home') }}"
                       class="px-5 py-3 rounded-lg border border-yellow-600/30 hover:bg-emerald-900/40 transition">Back to Home</a>

                    <button type="submit"
                            class="px-6 py-3 rounded-lg bg-gradient-to-r from-yellow-600 to-yellow-500 text-black font-bold hover:from-yellow-500 hover:to-yellow-400 transition shadow-lg"
                            style="box-shadow: 0 0 20px rgba(234, 179, 8, 0.35);">
                        Register Now
                    </button>
                </div>
            </form>
        </div>
    </section>
</div>

{{-- tiny helper to show payable amount --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    // --- Payable Amount Logic ---
    const radios = document.querySelectorAll('input[name="membership_plan"]');
    const registrationFeeInput = document.getElementById('registration-fee');
    const membershipFeeSpan = document.getElementById('membership-fee');
    const payableAmountSpan = document.getElementById('payable-amount');

    // Constants based on your request
    const REGISTRATION_FEE = 100; // ৳100
    const MONTHLY_FEE = 200; // ৳200
    const YEARLY_FEE = 2400; // ৳2400

    const setAmt = () => {
        const selectedRadio = document.querySelector('input[name="membership_plan"]:checked');
        const plan = selectedRadio ? selectedRadio.value : null;
        
        let initialMembershipFee = 0;
        let totalPayable = REGISTRATION_FEE;

        if (plan === 'monthly') {
            initialMembershipFee = MONTHLY_FEE;
        } else if (plan === 'yearly') {
            initialMembershipFee = YEARLY_FEE;
        }
        
        totalPayable += initialMembershipFee;

        // Update display spans
        membershipFeeSpan.textContent = `৳${initialMembershipFee.toLocaleString()}`;
        payableAmountSpan.textContent = `৳${totalPayable.toLocaleString()}`;

        // Ensure registration fee is always 100
        registrationFeeInput.value = REGISTRATION_FEE;
    };
    
    // Add event listeners
    radios.forEach(r => r.addEventListener('change', setAmt));
    
    // Set initial amount on load
    setAmt();

    // --- Password Toggle Logic ---
    const setupPasswordToggle = (inputId, buttonId) => {
        const passwordInput = document.getElementById(inputId);
        const toggleButton = document.getElementById(buttonId);

        if (!passwordInput || !toggleButton) return;

        const eyeIcon = toggleButton.querySelector('.eye-icon');
        const eyeSlashIcon = toggleButton.querySelector('.eye-slash-icon');

        toggleButton.addEventListener('click', function () {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeSlashIcon.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeSlashIcon.classList.add('hidden');
            }
        });
    };

    // Setup for both password fields
    setupPasswordToggle('password', 'togglePassword');
    setupPasswordToggle('password_confirmation', 'togglePasswordConfirmation');
});
</script>
@endsection