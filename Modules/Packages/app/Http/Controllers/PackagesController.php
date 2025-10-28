<?php

namespace Modules\Packages\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Carbon;
use Modules\Packages\Models\Package;
use Modules\Subscribers\Models\Subscriber;
use App\Http\Controllers\SslCommerzPaymentController;

class PackagesController extends Controller
{
    public function index()
    {
        $nowUtc = now()->utc();

        $packages = Package::query()
            ->where('status', 'active')
            ->where('visibility', 'public')
            ->where(function ($q) use ($nowUtc) {
                $q->whereNull('sale_ends_at')
                  ->orWhere('sale_ends_at', '>=', $nowUtc);
            })
            ->orderByRaw("FIELD(billing_period, 'one_time','monthly','yearly')")
            ->orderBy('price')
            ->paginate(12)
            ->withQueryString();

        return view('Frontend.packages', compact('packages'));
    }

    public function show(Package $package)
    {
        $nowUtc = now()->utc();

        $isPublicActive = ($package->status === 'active') && ($package->visibility === 'public');
        $startsOk = is_null($package->sale_starts_at) || $package->sale_starts_at->lte($nowUtc);
        $endsOk   = is_null($package->sale_ends_at)   || $package->sale_ends_at->gte($nowUtc);

        abort_unless($isPublicActive, 404);

        return view('Frontend.package-show', [
            'package' => $package,
            'canBuy'  => $startsOk && $endsOk,
        ]);
    }

    public function buyForm(Package $package)
    {
        return view('Frontend.package-buy', compact('package'));
    }

    public function buy(Request $request, Package $package)
    {
        $nowUtc = now()->utc();

        $isPublicActive = ($package->status === 'active') && ($package->visibility === 'public');
        $startsOk = is_null($package->sale_starts_at) || $package->sale_starts_at->lte($nowUtc);
        $endsOk   = is_null($package->sale_ends_at)   || $package->sale_ends_at->gte($nowUtc);

        if (!($isPublicActive && $startsOk && $endsOk)) {
            return back()->with('error', 'This package is not currently available to purchase.');
        }

        $validated = $request->validate([
            'full_name'        => ['required','string','max:255'],
            'username'         => ['required','string','max:100', Rule::unique('subscribers', 'username')],
            'phone'            => ['required','string','max:20'],
            'email'            => ['nullable','email','max:255'],
            'name_bn'          => ['nullable','string','max:255'],
            'dob'              => ['nullable','date'],
            'gender'           => ['nullable','in:male,female,other'],
            'blood_group'      => ['nullable','string','max:10'],
            'id_number'        => ['nullable','string','max:100'],
            'education'        => ['nullable','string','max:255'],
            'profession'       => ['nullable','string','max:255'],
            'expertise'        => ['nullable','string','max:255'],
            'country'          => ['nullable','string','max:100'],
            'division'         => ['nullable','string','max:100'],
            'district'         => ['nullable','string','max:100'],
            'address'          => ['nullable','string','max:500'],
            'is_student'       => ['nullable','boolean'],
            'profile_picture'  => ['nullable','image','max:2048'],
        ]);

        $profilePath = null;
        if ($request->hasFile('profile_picture')) {
            $profilePath = $request->file('profile_picture')->store('profiles', 'public');
        }

        $lookup = [];
        if (!empty($validated['email'])) {
            $lookup['email'] = $validated['email'];
        } else {
            $lookup['phone'] = $validated['phone'];
        }

        $subscriber = Subscriber::firstOrNew($lookup);
        if (!$subscriber->exists) {
            $subscriber->subscriber_id = 'S' . now()->format('ymd') . strtoupper(Str::random(4));
        }

        $subscriber->full_name       = $validated['full_name'];
        $subscriber->username        = $validated['username'];
        $subscriber->phone           = $validated['phone'];
        $subscriber->name_bn         = $validated['name_bn']   ?? null;
        $subscriber->dob             = !empty($validated['dob']) ? Carbon::parse($validated['dob'])->toDateString() : null;
        $subscriber->gender          = $validated['gender']    ?? null;
        $subscriber->blood_group     = $validated['blood_group'] ?? null;
        $subscriber->id_number       = $validated['id_number'] ?? null;
        $subscriber->education       = $validated['education'] ?? null;
        $subscriber->profession      = $validated['profession'] ?? null;
        $subscriber->other_expertise = $validated['expertise'] ?? null;
        $subscriber->country         = $validated['country']   ?? 'Bangladesh';
        $subscriber->division        = $validated['division']  ?? null;
        $subscriber->district        = $validated['district']  ?? null;
        $subscriber->address         = $validated['address']   ?? null;
        $subscriber->status          = 'active';
        $subscriber->profile_pic     = $profilePath;
        $subscriber->save();

        $tranId = 'POJ'.now()->format('ymd').strtoupper(Str::random(6));

        DB::table('member_payments')->insert([
            'tran_id'                 => $tranId,
            'amount'                  => (float) $package->price,
            'status'                  => 'pending',
            'package_id'              => $package->id,
            'package_name'            => $package->name,
            'full_name'               => $validated['full_name'],
            'name_bn'                 => $validated['name_bn']    ?? null,
            'username'                => $validated['username'],
            'email'                   => $validated['email']      ?? null,
            'phone'                   => $validated['phone'],
            'profile_pic'             => $profilePath,
            'dob'                     => $validated['dob']        ?? null,
            'id_number'               => $validated['id_number']  ?? null,
            'gender'                  => $validated['gender']     ?? null,
            'blood_group'             => $validated['blood_group']?? null,
            'education_qualification' => $validated['education']  ?? null,
            'profession'              => $validated['profession'] ?? null,
            'other_expertise'         => $validated['expertise']  ?? null,
            'country'                 => $validated['country']    ?? 'Bangladesh',
            'division'                => $validated['division']   ?? null,
            'district'                => $validated['district']   ?? null,
            'address'                 => $validated['address']    ?? null,
            'membership_type'         => ($request->boolean('is_student') ? 'Student' : 'General'),
            'created_at'              => now(),
            'updated_at'              => now(),
        ]);

        return redirect()->route('sslc.init', ['tran_id' => $tranId]);
    }
}
