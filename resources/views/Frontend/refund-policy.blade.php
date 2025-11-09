{{-- resources/views/frontend/refund-policy.blade.php --}}
@extends('frontend.layout')

@section('title', 'Refund Policy')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-12">
    <h1 class="text-3xl font-extrabold mb-4">Refund & Return Policy</h1>
    <p class="text-sm text-gray-600 mb-6">Last updated: {{ date('F j, Y') }}</p>

    <section class="mb-6">
        <h2 class="text-xl font-semibold mb-2">1. Overview</h2>
        <p class="leading-relaxed">
            We use <strong>SSLCOMMERZ</strong> as our official payment gateway for all online transactions. 
            If you require a refund, please contact our support team first. 
            Refund requests are processed by the merchant and completed through the SSLCOMMERZ refund system.
        </p>
    </section>

    <section class="mb-6">
        <h2 class="text-xl font-semibold mb-2">2. How to Request a Refund</h2>
        <ol class="list-decimal ml-6 space-y-2">
            <li>
                Contact us at 
                <a href="mailto:info@prokritiojibon.org" class="underline text-amber-500">info@prokritiojibon.org</a> 
                with the following details:
                <ul class="list-disc ml-6 mt-2">
                    <li>Your full name and email address</li>
                    <li>Order ID / Invoice number</li>
                    <li>SSLCOMMERZ Transaction ID (if available)</li>
                    <li>Refund amount and reason</li>
                </ul>
            </li>
            <li>We will verify your request and, if applicable, initiate the refund through our SSLCOMMERZ merchant dashboard or via their Refund API.</li>
            <li>Once the refund is initiated, the credit to your bank or card account will depend on your issuing bank’s processing time.</li>
        </ol>
    </section>

    <section class="mb-6">
        <h2 class="text-xl font-semibold mb-2">3. Refund Timeline</h2>
        <p class="leading-relaxed">
            According to SSLCOMMERZ guidelines, once a refund is initiated by the merchant, 
            it typically takes <strong>5–10 working days</strong> to reflect in your account. 
            However, the final posting time depends on your card issuer or bank.
        </p>
    </section>

    <section class="mb-6">
        <h2 class="text-xl font-semibold mb-2">4. Merchant & SSLCOMMERZ Rights</h2>
        <p class="leading-relaxed">
            In cases of suspected fraud, chargebacks, invalid card details, or policy violations, 
            SSLCOMMERZ and/or the acquiring bank reserve the right to hold or reverse a transaction. 
            Merchants are obligated to comply with SSLCOMMERZ’s verification and refund process.
        </p>
    </section>

    <section class="mb-6">
        <h2 class="text-xl font-semibold mb-2">5. Querying Refund Status</h2>
        <p class="leading-relaxed">
            Refund statuses can be checked through the SSLCOMMERZ merchant dashboard or the Refund Status API. 
            For any updates, please contact our support with your order ID and transaction details.
        </p>
    </section>

    <section class="mb-6">
        <h2 class="text-xl font-semibold mb-2">6. Contact Information</h2>
        <ul class="list-disc ml-6">
            <li>Email (Merchant): <a href="mailto:info@prokritiojibon.org" class="underline text-amber-500">info@prokritiojibon.org</a></li>
            <li>Gateway Support (SSLCOMMERZ): <a href="mailto:support@sslcommerz.com" class="underline text-amber-500">support@sslcommerz.com</a></li>
        </ul>
    </section>

    <footer class="text-sm text-gray-600 mt-8 border-t pt-4">
        <p>
            <strong>Note:</strong> This refund policy is aligned with the official SSLCOMMERZ merchant guidelines. 
            The final refund completion time depends on your bank and SSLCOMMERZ’s internal processing.
        </p>
    </footer>
</div>
@endsection
