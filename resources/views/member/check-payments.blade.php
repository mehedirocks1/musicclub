@extends('member.layout')

@section('title', 'Payment History - POJ Music Club')

@section('content')
<div class="max-w-7xl mx-auto mt-8 p-4">
    <h2 class="text-2xl font-bold mb-4 text-amber-400">Payment History</h2>

    <div class="overflow-x-auto bg-gray-800 rounded shadow">
        <table class="min-w-full divide-y divide-gray-700">
            <thead class="bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Email / Phone</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Transaction ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">Member / Subscriber ID</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @forelse($payments as $payment)
                    <tr class="hover:bg-gray-700">
                        <td class="px-6 py-4 text-gray-200">{{ $payment->created_at->format('d M, Y H:i') }}</td>
                        <td class="px-6 py-4 text-gray-200">
                            {{ $payment->member->full_name ?? $payment->subscriber->full_name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-gray-200">
                            {{ $payment->member->email ?? $payment->subscriber->email ?? $payment->email ?? $payment->phone ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-gray-200">৳{{ number_format($payment->amount, 2) }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 text-sm rounded-full
                                @if($payment->status === 'paid') bg-green-800 text-green-300
                                @elseif($payment->status === 'pending') bg-yellow-800 text-yellow-300
                                @elseif($payment->status === 'failed') bg-red-800 text-red-300
                                @elseif($payment->status === 'cancelled') bg-gray-700 text-gray-300
                                @else bg-red-900 text-red-200 @endif">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-200">{{ $payment->tran_id }}</td>
                        <td class="px-6 py-4 text-gray-200">
                            {{ $payment->member->member_id ?? $payment->subscriber->subscriber_id ?? 'N/A' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-400">No payments found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($payments instanceof \Illuminate\Contracts\Pagination\Paginator || $payments instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
        <div class="mt-4">
            {{ $payments->links() }}
        </div>
    @endif
</div>
@endsection
