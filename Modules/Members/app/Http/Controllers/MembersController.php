<?php

namespace Modules\Members\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;
use Modules\Members\Models\Member;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
class MembersController extends Controller
{
     use AuthorizesRequests;
    /**
     * Dashboard page
     */
    public function dashboard()
    {
        $member = Auth::guard('member')->user();
        return view('member.dashboard', compact('member'));
    }

    /**
     * Show member profile
     */
    public function profile()
    {
        $member = Auth::guard('member')->user();
        return view('member.profile', compact('member'));
    }

    /**
     * Update member profile
     */
    public function updateProfile(Request $request)
    {
        $member = auth('member')->user();

        // Validate input including profile_pic
        $data = $request->validate([
            'full_name'        => 'required|string|max:255',
            'email'            => 'required|email|max:255',
            'phone'            => 'nullable|string|max:50',
            'father_name'      => 'nullable|string|max:255',
            'mother_name'      => 'nullable|string|max:255',
            'dob'              => 'nullable|date',
            'id_number'        => 'nullable|string|max:50',
            'gender'           => 'nullable|string|max:10',
            'blood_group'      => 'nullable|string|max:5',
            'profession'       => 'nullable|string|max:255',
            'other_expertise'  => 'nullable|string|max:255',
            'country'          => 'nullable|string|max:255',
            'division'         => 'nullable|string|max:255',
            'district'         => 'nullable|string|max:255',
            'address'          => 'nullable|string|max:500',
            'profile_pic'      => 'nullable|image|max:2048', // max 2MB
        ]);

        // Handle profile picture upload
        if ($request->hasFile('profile_pic')) {
            $file = $request->file('profile_pic');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('profile_pics', $filename, 'public');

            // Update profile_pic in $data
            $data['profile_pic'] = $filePath;
        }

        // Update member data
        $member->update($data);

        return redirect()->route('member.profile')->with('success', 'Profile updated successfully');
    }

    /**
     * Show change password page
     */
    public function changePassword()
    {
        return view('member.change-password');
    }

    /**
     * Update member password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        $member = Auth::guard('member')->user();

        if (!Hash::check($request->current_password, $member->password)) {
            return back()->withErrors(['current_password' => 'Current password does not match']);
        }

        $member->password = Hash::make($request->password);
        $member->save();

        return redirect()->route('member.dashboard')->with('success', 'Password updated successfully');
    }

    /**
     * Pay Fee page
     */
    public function payFee()
    {
        $member = Auth::guard('member')->user();
        return view('member.pay-fee', compact('member'));
    }

    /**
     * Check Payments page
     */
    public function checkPayments()
    {
        $member = Auth::guard('member')->user();
        return view('member.check-payments', compact('member'));
    }

    /**
     * Optional: fallback index for resource routes
     */
    public function index()
    {
        return redirect()->route('member.dashboard');
    }

    /**
     * Download member ID card as PDF (barryvdh/laravel-dompdf)
     *
     * @param  int|string  $id
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function memberCard($id)
    {
        $member = Member::findOrFail($id);

        $data = ['allData' => $member];

        $pdf = Pdf::loadView('backend.customer.member-card', $data)
            ->setOptions([
                'defaultFont' => 'sans-serif',
                'isRemoteEnabled' => true,
            ])
            ->setPaper('a4', 'portrait');

        $filename = 'member-card-' . ($member->member_id ?? $member->id) . '.pdf';

        return response()->streamDownload(
            fn () => print($pdf->output()),
            $filename
        );
    }

    /**
     * Export all members to Excel directly from controller
     */
    public function export(Request $request)
    {
        $this->authorize('viewAny', Member::class); // optional policy check

        $members = Member::query()
            ->select([
                'member_id',
                'username',
                'full_name',
                'name_bn',
                'email',
                'phone',
                'gender',
                'blood_group',
                'membership_type',
                'membership_plan',
                'membership_status',
                'status',
                'balance',
                'registration_date',
                'district',
                'division',
                'country',
            ])
            ->orderBy('id')
            ->get();

        $export = new class($members) implements FromCollection, WithHeadings {
            protected $members;
            public function __construct($members)
            {
                $this->members = $members;
            }

            public function collection()
            {
                return $this->members->map(function ($m) {
                    return [
                        $m->member_id,
                        $m->username,
                        $m->full_name,
                        $m->name_bn,
                        $m->email,
                        $m->phone,
                        $m->gender,
                        $m->blood_group,
                        $m->membership_type,
                        $m->membership_plan,
                        $m->membership_status,
                        $m->status,
                        number_format((float)$m->balance, 2),
                        optional($m->registration_date)->format('Y-m-d'),
                        $m->district,
                        $m->division,
                        $m->country,
                    ];
                });
            }

            public function headings(): array
            {
                return [
                    'Member ID',
                    'Username',
                    'Full Name',
                    'Name (BN)',
                    'Email',
                    'Phone',
                    'Gender',
                    'Blood Group',
                    'Membership Type',
                    'Membership Plan',
                    'Membership Status',
                    'Account Status',
                    'Balance (BDT)',
                    'Registration Date',
                    'District',
                    'Division',
                    'Country',
                ];
            }
        };

        $fileName = 'members-' . now()->format('Ymd-His') . '-' . Str::random(6) . '.xlsx';

        return Excel::download($export, $fileName);
    }
}
