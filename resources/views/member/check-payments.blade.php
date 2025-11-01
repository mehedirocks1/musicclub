<x-member-layout>
    <h2 class="text-2xl font-bold mb-4">Payment History</h2>

    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($payments as $payment)
                    <tr>
                        <td class="px-6 py-4">{{ $payment->created_at->format('d M, Y') }}</td>
                        <td class="px-6 py-4">${{ number_format($payment->amount, 2) }}</td>
                        <td class="px-6 py-4">{{ ucfirst($payment->status) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center text-gray-400">No payments found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-member-layout>
