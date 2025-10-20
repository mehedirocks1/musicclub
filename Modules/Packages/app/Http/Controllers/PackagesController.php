<?php

namespace Modules\Packages\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Packages\Models\Package;

class PackagesController extends Controller
{
    public function index()
    {
        $nowUtc = now()->utc(); // DB timestamps are UTC

        $packages = Package::query()
            ->where('status', 'active')
            ->where('visibility', 'public')
            // 🔑 শুধু expire হয়নি এমন প্যাকেজ দেখাও (upcoming + running)
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

        $isPublicActive = ($package->status === 'active')
            && ($package->visibility === 'public')
            && is_null($package->deleted_at);

        $inWindowToBuy =
            (is_null($package->sale_starts_at) || $package->getRawOriginal('sale_starts_at') <= $nowUtc) &&
            (is_null($package->sale_ends_at)   || $package->getRawOriginal('sale_ends_at')   >= $nowUtc);

        abort_unless($isPublicActive, 404);

        // চাইলে blade-এ বাটন ডিসেবল করতে এই ভ্যার পাঠাতে পারো
        return view('Frontend.package-show', [
            'package' => $package,
            'canBuy'  => $inWindowToBuy,
        ]);
    }

    public function buy(Request $request, Package $package)
    {
        $nowUtc = now()->utc();

        $ok =
            ($package->status === 'active') &&
            ($package->visibility === 'public') &&
            (is_null($package->sale_starts_at) || $package->getRawOriginal('sale_starts_at') <= $nowUtc) &&
            (is_null($package->sale_ends_at)   || $package->getRawOriginal('sale_ends_at')   >= $nowUtc) &&
            is_null($package->deleted_at);

        abort_unless($ok, 404);

        return redirect()
            ->route('frontend.packages.index')
            ->with('success', "Proceed to buy: {$package->name} ({$package->currency} " . number_format($package->price, 2) . ")");
    }
}
