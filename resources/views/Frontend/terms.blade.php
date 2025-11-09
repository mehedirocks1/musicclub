{{-- resources/views/frontend/terms.blade.php --}}
@extends('frontend.layout')

@section('title', 'Terms & Conditions')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-12">
    {{-- Use a slightly translucent panel so text reads well on dark backgrounds --}}
    <div class="p-8 rounded-lg bg-opacity-30 bg-black/60">
        <h1 class="text-3xl font-extrabold mb-4 text-white">Terms & Conditions</h1>
        <p class="text-sm text-gray-300 mb-8">Last updated: {{ date('F j, Y') }}</p>

        <nav class="mb-8">
            <ul class="flex flex-wrap gap-3 text-sm">
                <li><a href="#introduction" class="underline text-amber-300 hover:text-amber-400">Introduction</a></li>
                <li><a href="#use-of-site" class="underline text-amber-300 hover:text-amber-400">Use of Site</a></li>
                <li><a href="#user-content" class="underline text-amber-300 hover:text-amber-400">User Content</a></li>
                <li><a href="#intellectual-property" class="underline text-amber-300 hover:text-amber-400">Intellectual Property</a></li>
                <li><a href="#liability" class="underline text-amber-300 hover:text-amber-400">Limitation of Liability</a></li>
                <li><a href="#governing-law" class="underline text-amber-300 hover:text-amber-400">Governing Law</a></li>
                <li><a href="#contact" class="underline text-amber-300 hover:text-amber-400">Contact</a></li>
            </ul>
        </nav>

        <section id="introduction" class="mb-8">
            <h2 class="text-2xl font-semibold mb-2 text-white">1. Introduction</h2>
            <p class="text-base text-gray-200 leading-relaxed">
                Welcome to POJ Music Club. These Terms & Conditions govern your use of our website and services.
                By accessing or using the site you agree to be bound by these terms. If you do not agree, please do not use the site.
            </p>
        </section>

        <section id="use-of-site" class="mb-8">
            <h2 class="text-2xl font-semibold mb-2 text-white">2. Use of the Site</h2>
            <p class="text-base text-gray-200 leading-relaxed">
                You agree to use the site only for lawful purposes and in a way that does not infringe the rights of others
                or restrict their use of the site. You must not misuse our services (including hacking, scraping, or introducing malware).
            </p>
        </section>

        <section id="user-content" class="mb-8">
            <h2 class="text-2xl font-semibold mb-2 text-white">3. User Content</h2>
            <p class="text-base text-gray-200 leading-relaxed">
                If you submit content (comments, uploads, messages), you represent that you have the right to do so.
                You grant us a non-exclusive, transferable, sublicensable, royalty-free license to host, use, and display that content in connection with the service.
                Do not post illegal, infringing, or offensive content.
            </p>
        </section>

        <section id="intellectual-property" class="mb-8">
            <h2 class="text-2xl font-semibold mb-2 text-white">4. Intellectual Property</h2>
            <p class="text-base text-gray-200 leading-relaxed">
                All site content — including logos, text, images, and designs — is owned or licensed by Prokriti O Jibon Foundation (POJ Music) unless otherwise noted.
                You may not reproduce or redistribute site materials without prior written permission.
            </p>
        </section>

        <section id="liability" class="mb-8">
            <h2 class="text-2xl font-semibold mb-2 text-white">5. Limitation of Liability</h2>
            <p class="text-base text-gray-200 leading-relaxed">
                To the fullest extent permitted by law, POJ Music will not be liable for any direct, indirect, incidental, consequential,
                or punitive damages arising from your access to or use of the site.
            </p>
        </section>

        <section id="governing-law" class="mb-8">
            <h2 class="text-2xl font-semibold mb-2 text-white">6. Governing Law</h2>
            <p class="text-base text-gray-200 leading-relaxed">
                These terms are governed by the laws of Bangladesh. Any dispute will be resolved in the courts located in Bangladesh,
                unless otherwise agreed in writing.
            </p>
        </section>

        <section id="contact" class="mb-8">
            <h2 class="text-2xl font-semibold mb-2 text-white">7. Contact</h2>
            <p class="text-base text-gray-200 leading-relaxed">
                For questions about these terms, contact us at:
            </p>
            <ul class="list-disc ml-5 mt-3 text-gray-300">
                <li>Website: <a href="{{ url('/') }}" class="underline text-amber-300 hover:text-amber-400">{{ url('/') }}</a></li>
                <li>Email: <a href="mailto:info@prokritiojibon.org" class="underline text-amber-300 hover:text-amber-400">info@prokritiojibon.org</a></li>
            </ul>
        </section>

        <footer class="mt-12 text-sm text-gray-400">
            <p>These terms may be updated from time to time. Please check this page periodically for changes.</p>
        </footer>
    </div>
</div>
@endsection
