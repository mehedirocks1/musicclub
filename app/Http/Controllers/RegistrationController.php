<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\MemberPayment;
use Modules\Members\Models\Member;
use DevWizard\Textify\Facades\Textify;  // ADD ONLY

class RegistrationController extends Controller
{
    public function create()
    {
        return view('Frontend.register');
    }

    public function store(Request $request)
    {
        // 1) Validate (password is now required)
        $data = $request->validate([
            'full_name'               => ['required', 'string', 'max:255'],
            'name_bn'                 => ['nullable', 'string', 'max:255'],
            'username'                => ['required', 'string', 'max:50'], // uniqueness enforced at creation
            'email'                   => ['nullable', 'email', 'max:255'],
            'phone'                   => ['nullable', 'string', 'max:20'],
            'dob'                     => ['nullable', 'date'],
            'gender'                  => ['nullable', Rule::in(Member::GENDERS)],
            'blood_group'             => ['nullable', Rule::in(Member::BLOOD_GROUPS)],
            'id_number'               => ['nullable', 'string', 'max:100'],
            'education_qualification' => ['nullable', 'string', 'max:255'],
            'profession'              => ['nullable', 'string', 'max:255'],
            'other_expertise'         => ['nullable', 'string'],
            'country'                 => ['nullable', 'string', 'max:255'],
            'division'                => ['nullable', 'string', 'max:255'],
            'district'                => ['nullable', 'string', 'max:255'],
            'address'                 => ['nullable', 'string'],
            'membership_type'         => ['required', Rule::in(Member::MEMBERSHIP_TYPES)],
            'profile_pic'             => ['nullable', 'image', 'max:2048'],

            // Plan required for fee
            'membership_plan'         => ['required', 'in:monthly,yearly'],

            // Password is now required
            'password'                => ['required', 'string', 'min:6', 'confirmed'],
            'password_confirmation'   => ['required', 'string', 'min:6'],
        ]);

        // 2) Normalize
        $data['country'] = $data['country'] ?? 'Bangladesh';
        if (!empty($data['email'])) {
            $data['email'] = strtolower(trim($data['email']));
        }

        // 3) Upload profile pic (path snapshot)
        $profilePath = null;
        if ($request->hasFile('profile_pic')) {
            $profilePath = $request->file('profile_pic')->store('members/profile_pics', 'public');
        }

        // 4) Server-side fee
        $amount = $data['membership_plan'] === 'yearly' ? 2000.00 : 200.00;

        // 5) Prepare password_hash for the snapshot:
        // Password is required, so we always hash it.
        $passwordHash = Hash::make($data['password']);

        // 6) Create pending payment snapshot (member_id NULL)
        // NOTE: Ensure your `member_payments` table has a `password_hash` nullable column (string 255).
        $payment = MemberPayment::create([
            'member_id'               => null,
            'tran_id'                 => 'INV-'.Str::uuid()->toString(),
            'plan'                    => $data['membership_plan'],
            'amount'                  => $amount,
            'currency'                => 'BDT',
            'status'                  => 'pending',

            // Snapshot (form data)
            'full_name'               => $data['full_name'],
            'name_bn'                 => $data['name_bn'] ?? null,
            'username'                => $data['username'],
            'email'                   => $data['email'] ?? null,
            'phone'                   => $data['phone'] ?? null,
            'dob'                     => $data['dob'] ?? null,
            'gender'                  => $data['gender'] ?? null,
            'blood_group'             => $data['blood_group'] ?? null,
            'id_number'               => $data['id_number'] ?? null,
            'education_qualification' => $data['education_qualification'] ?? null,
            'profession'              => $data['profession'] ?? null,
            'other_expertise'         => $data['other_expertise'] ?? null,
            'country'                 => $data['country'] ?? null,
            'division'                => $data['division'] ?? null,
            'district'                => $data['district'] ?? null,
            'address'                 => $data['address'] ?? null,
            'membership_type'         => $data['membership_type'],
            'profile_pic'             => $profilePath,

            // store the hashed password in snapshot to use later when creating Member
            'password_hash'           => $passwordHash,
        ]);

        // 7) Redirect to SSLCommerz init
        return redirect()->route('sslc.init', [
            'tran_id' => $payment->tran_id,
        ]);
    }

    /**
     * Example handler you can call after SSLCommerz / payment success (IPN or return URL).
     * Adapt names/logic to your actual payment workflow.
     *
     * This reads the payment snapshot and creates a Member record (if not exists).
     */
    public function handleSslcSuccess(Request $request)
    {
        // Example: receive tran_id from SSLC success callback
        $tranId = $request->input('tran_id');
        if (! $tranId) {
            return abort(400, 'tran_id is required.');
        }

        $payment = MemberPayment::where('tran_id', $tranId)->first();

        if (! $payment) {
            // log and abort
            return abort(404, 'Payment snapshot not found.');
        }

        // Basic payment verification should be here (IPN / gateway verification).
        // For brevity we assume the gateway verified the payment successfully.
        $payment->status = 'completed';
        $payment->save();

        // If member already created for this payment, skip creation
        if ($payment->member_id) {
            $member = Member::find($payment->member_id);
            if ($member) {
                Auth::login($member);
                return redirect()->route('member.dashboard')->with('success', 'Payment received. Welcome back!');
            }
            // If member_id present but member missing, continue to create a fresh member.
        }

        // 1) Ensure username/email uniqueness. Make safe unique adjustments if needed.
        $username = $payment->username;
        $email = $payment->email;

        // Ensure username exists (registration required it). If missing, build one.
        if (empty($username)) {
            $username = 'member_' . Str::random(6);
        }

        // Make username unique by appending suffix until unique
        $baseUsername = $username;
        $attempt = 0;
        while (Member::where('username', $username)->exists()) {
            $attempt++;
            $username = $baseUsername . '_' . Str::random(4);
            if ($attempt > 10) {
                // fallback to guaranteed unique
                $username = $baseUsername . '_' . time();
                break;
            }
        }

        // If email exists in members, avoid conflict: append a unique token to local part (or nullify)
        if ($email) {
            $existing = Member::where('email', $email)->exists();
            if ($existing) {
                // Option chosen: append token to local part to avoid collision and keep an email-like value
                if (strpos($email, '@') !== false) {
                    [$local, $domain] = explode('@', $email, 2);
                    $email = $local . '+dup' . Str::random(4) . '@' . $domain;
                } else {
                    $email = null;
                }
            }
        }

        // 2) Determine password to store on Member:
        // Since password was required at registration, password_hash will always exist.
        // The fallback logic for random password is no longer needed.
        $finalPasswordHash = $payment->password_hash;
        
        if (empty($finalPasswordHash)) {
             // This should not happen if password is required.
             // But as a safety fallback, we still generate one.
             \Illuminate\Support\Facades\Log::warning('Password_hash was missing from payment snapshot, generating random password.', ['tran_id' => $tranId]);
             $finalPasswordHash = Hash::make(Str::random(12));
             // You should notify the user or force a password reset here.
        }

        // 3) Create Member record
        // Use mass assignment; ensure Member::$fillable has the below fields.
        // We assume Member model does not double-hash the password on set (if it does, adjust accordingly).
        $member = Member::create([
            'full_name'               => $payment->full_name,
            'name_bn'                 => $payment->name_bn,
            'username'                => $username,
            'email'                   => $email,
            'phone'                   => $payment->phone,
            'dob'                     => $payment->dob,
            'gender'                  => $payment->gender,
            'blood_group'             => $payment->blood_group,
            'id_number'               => $payment->id_number,
            'education_qualification' => $payment->education_qualification,
            'profession'              => $payment->profession,
            'other_expertise'         => $payment->other_expertise,
            'country'                 => $payment->country,
            'division'                => $payment->division,
            'district'                => $payment->district,
            'address'                 => $payment->address,
            'membership_type'         => $payment->membership_type,
            'profile_pic'             => $payment->profile_pic,
            // Store the pre-computed hash directly
            'password'                => $finalPasswordHash,
            // other member fields...
        ]);

        // 4) Link payment to member
        $payment->member_id = $member->id;
        $payment->save();

        // 5) Login the user and redirect
        Auth::login($member);

        return redirect()->route('member.dashboard')->with('success', 'Registration successful. Welcome!');
    }
}