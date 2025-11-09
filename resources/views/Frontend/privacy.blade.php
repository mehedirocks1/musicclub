{{-- resources/views/frontend/privacy.blade.php --}}
@extends('frontend.layout')

@section('title', 'Privacy Policy')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-12">
    <div class="p-8 rounded-lg bg-black/60 bg-opacity-40">
        <h1 class="text-3xl font-extrabold mb-4 text-white">Privacy Policy</h1>
        <p class="text-sm text-gray-300 mb-8">Last updated: {{ date('F j, Y') }}</p>

        <nav class="mb-8">
            <ul class="flex flex-wrap gap-3 text-sm">
                <li><a href="#introduction" class="underline text-amber-300 hover:text-amber-400">Introduction</a></li>
                <li><a href="#information" class="underline text-amber-300 hover:text-amber-400">Information We Collect</a></li>
                <li><a href="#use" class="underline text-amber-300 hover:text-amber-400">How We Use Information</a></li>
                <li><a href="#sharing" class="underline text-amber-300 hover:text-amber-400">Information Sharing</a></li>
                <li><a href="#security" class="underline text-amber-300 hover:text-amber-400">Data Security</a></li>
                <li><a href="#cookies" class="underline text-amber-300 hover:text-amber-400">Cookies</a></li>
                <li><a href="#rights" class="underline text-amber-300 hover:text-amber-400">Your Rights</a></li>
                <li><a href="#changes" class="underline text-amber-300 hover:text-amber-400">Changes to This Policy</a></li>
                <li><a href="#contact" class="underline text-amber-300 hover:text-amber-400">Contact Us</a></li>
            </ul>
        </nav>

        <section id="introduction" class="mb-8">
            <h2 class="text-2xl font-semibold mb-2 text-white">1. Introduction</h2>
            <p class="text-base text-gray-200 leading-relaxed">
                POJ Music Club, operated under Prokriti O Jibon Foundation, respects your privacy and is committed to protecting your personal data.
                This policy explains how we collect, use, and protect information you share with us through our website and related services.
            </p>
        </section>

        <section id="information" class="mb-8">
            <h2 class="text-2xl font-semibold mb-2 text-white">2. Information We Collect</h2>
            <p class="text-base text-gray-200 leading-relaxed mb-2">
                We may collect the following types of information:
            </p>
            <ul class="list-disc ml-6 text-gray-300 leading-relaxed">
                <li>Personal details (name, email, phone number, address)</li>
                <li>Account registration and payment information</li>
                <li>Browsing activity, device information, and IP address</li>
                <li>Communications you send to us (feedback, support requests, etc.)</li>
            </ul>
        </section>

        <section id="use" class="mb-8">
            <h2 class="text-2xl font-semibold mb-2 text-white">3. How We Use Information</h2>
            <p class="text-base text-gray-200 leading-relaxed">
                We use your data to:
            </p>
            <ul class="list-disc ml-6 text-gray-300 leading-relaxed">
                <li>Provide and improve our services</li>
                <li>Communicate with you about updates, events, and offers</li>
                <li>Process transactions and manage memberships</li>
                <li>Enhance security and prevent fraudulent activity</li>
                <li>Comply with legal obligations</li>
            </ul>
        </section>

        <section id="sharing" class="mb-8">
            <h2 class="text-2xl font-semibold mb-2 text-white">4. Information Sharing</h2>
            <p class="text-base text-gray-200 leading-relaxed">
                We do not sell or rent your personal data. We may share limited information with trusted service providers
                who assist us in operating the website or delivering services, bound by confidentiality agreements.
                We may also share data if required by law or to protect our legal rights.
            </p>
        </section>

        <section id="security" class="mb-8">
            <h2 class="text-2xl font-semibold mb-2 text-white">5. Data Security</h2>
            <p class="text-base text-gray-200 leading-relaxed">
                We implement appropriate technical and organizational measures to safeguard your data against unauthorized
                access, alteration, disclosure, or destruction. However, no method of internet transmission is 100% secure.
            </p>
        </section>

        <section id="cookies" class="mb-8">
            <h2 class="text-2xl font-semibold mb-2 text-white">6. Cookies</h2>
            <p class="text-base text-gray-200 leading-relaxed">
                Our website uses cookies to improve user experience, analyze traffic, and personalize content.
                You can control or disable cookies through your browser settings, but some site features may not work properly if disabled.
            </p>
        </section>

        <section id="rights" class="mb-8">
            <h2 class="text-2xl font-semibold mb-2 text-white">7. Your Rights</h2>
            <p class="text-base text-gray-200 leading-relaxed">
                You have the right to access, correct, or delete your personal data, as well as withdraw consent for data processing.
                To exercise these rights, please contact us using the information below.
            </p>
        </section>

        <section id="changes" class="mb-8">
            <h2 class="text-2xl font-semibold mb-2 text-white">8. Changes to This Policy</h2>
            <p class="text-base text-gray-200 leading-relaxed">
                We may update this Privacy Policy from time to time to reflect legal or operational changes.
                Updated versions will be posted on this page with the revised date.
            </p>
        </section>

        <section id="contact" class="mb-8">
            <h2 class="text-2xl font-semibold mb-2 text-white">9. Contact Us</h2>
            <p class="text-base text-gray-200 leading-relaxed">
                For any questions or requests regarding this Privacy Policy, please contact us at:
            </p>
            <ul class="list-disc ml-5 mt-3 text-gray-300">
                <li>Website: <a href="{{ url('/') }}" class="underline text-amber-300 hover:text-amber-400">{{ url('/') }}</a></li>
                <li>Email: <a href="mailto:info@prokritiojibon.org" class="underline text-amber-300 hover:text-amber-400">info@prokritiojibon.org</a></li>
            </ul>
        </section>

        <footer class="mt-12 text-sm text-gray-400">
            <p>By using our website, you consent to this Privacy Policy.</p>
        </footer>
    </div>
</div>
@endsection
