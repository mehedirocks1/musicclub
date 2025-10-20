<?php
namespace Modules\Orders\Http\Controllers;
use Illuminate\Routing\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Str;                 // ট্রানজ্যাকশন/সেশন আইডি জেনারেট করতে
use Illuminate\Support\Facades\Log;         // ডিবাগ লগ
use Illuminate\Support\Facades\DB;          // DB::transaction ইত্যাদি (যদি ব্যবহার করো)
use Raziul\Sslcommerz\Facades\Sslcommerz;   // SSLCommerz গেটওয়ে
use App\Models\MemberPayment;               // তোমার পেমেন্ট মডেল (পাথ ঠিক আছে তো?)
use Modules\Members\Models\Member;   
use DevWizard\Textify\Facades\Textify;   // ADD ONLY


class CheckoutController extends Controller
{
    public function init(Request $request)
    {
        // buyer_type: 'subscriber' | 'member' (optional); এখানে আমরা subscriber ধরছি
        $packageId = (int) $request->input('package_id');
        $pkg = Package::where('id',$packageId)->where('status','active')->firstOrFail();

        $buyer = auth('subscriber')->user(); // subscriber গার্ড
        if (!$buyer) {
            // guest -> quick create minimal subscriber or redirect to login
            return redirect()->route('login')->with('error','Please login as subscriber.');
        }

        $order = DB::transaction(function () use ($buyer, $pkg) {
            $subtotal = $pkg->price;
            $tax = round(($pkg->tax_rate ?? 0) * $subtotal / 100, 2);
            $discount = 0;
            $total = $subtotal + $tax - $discount;

            $o = Order::create([
                'buyer_type' => get_class($buyer),
                'buyer_id'   => $buyer->id,
                'order_code' => 'POJ'.now()->format('ymd').Str::upper(Str::random(5)),
                'status'     => 'pending',
                'gateway'    => 'sslcommerz',
                'currency'   => $pkg->currency ?: 'BDT',
                'subtotal'   => $subtotal,
                'tax'        => $tax,
                'discount'   => $discount,
                'total'      => $total,
            ]);

            OrderItem::create([
                'order_id'  => $o->id,
                'package_id'=> $pkg->id,
                'qty'       => 1,
                'unit_price'=> $pkg->price,
                'line_total'=> $total,
            ]);

            return $o;
        });

        // TODO: integrate real SSLCommerz init here; for now redirect to fake success
        return redirect()->route('orders.success', ['order'=>$order->id, 'tran_id'=>Str::uuid()]);
    }

    public function success(Request $request)
    {
        $order = Order::findOrFail($request->query('order'));
        $tranId = $request->query('tran_id');

        if ($order->status !== 'paid') {
            DB::transaction(function () use ($order, $tranId) {
                $order->update([
                    'status' => 'paid',
                    'tran_id'=> $tranId,
                    'paid_at'=> now(),
                ]);

                foreach ($order->items as $item) {
                    $starts = now();
                    $expires = null;
                    if ($item->package->billing_period === 'one_time' && $item->package->access_duration_days) {
                        $expires = now()->addDays($item->package->access_duration_days);
                    }
                    $item->update([
                        'access_starts_at' => $starts,
                        'access_expires_at'=> $expires,
                    ]);
                }

                // snapshot buyer last payment (subscriber case)
                if ($order->buyer && method_exists($order->buyer, 'update')) {
                    $order->buyer->update([
                        'last_payment_amount'  => $order->total,
                        'last_payment_tran_id' => $tranId,
                        'last_payment_at'      => now(),
                        'last_payment_gateway' => $order->gateway,
                    ]);
                }
            });
        }

        return view('orders::success', compact('order'));
    }

    // IPN/webhook endpoint—SSLCommerz থেকে কল হবে
    public function ipn(Request $request)
    {
        // TODO: verify signature/status with gateway; then mark as paid/failed
        return response()->json(['ok' => true]);
    }
}