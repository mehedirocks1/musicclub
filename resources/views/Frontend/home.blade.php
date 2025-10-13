@extends('frontend.layout')

@section('title', 'POJ Music Club - Where Passion Meets Melody')

@section('content')
    {{-- Hero + Sections --}}
    <section id="home" class="relative h-screen">
        <!-- Hero Slider -->
        <div id="heroSlider" class="absolute inset-0 z-10">
            <!-- Slide 1 -->
            <div class="slide active">
                <div class="absolute inset-0 bg-gradient-to-r from-black via-black/70 to-transparent"></div>
                <img src="https://images.unsplash.com/photo-1511735111819-9a3f7709049c?w=1200&h=600&fit=crop" alt="Musicians playing in a dimly lit room" class="w-full h-full object-cover">
            </div>
            <!-- Slide 2 -->
            <div class="slide">
                <div class="absolute inset-0 bg-gradient-to-r from-black via-black/70 to-transparent"></div>
                <img src="https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?w=1200&h=600&fit=crop" alt="Crowd at a live music concert" class="w-full h-full object-cover">
            </div>
            <!-- Slide 3 -->
            <div class="slide">
                <div class="absolute inset-0 bg-gradient-to-r from-black via-black/70 to-transparent"></div>
                <img src="https://images.unsplash.com/photo-1514320291840-2e0a9bf2a9ae?w=1200&h=600&fit=crop" alt="DJ performing at a nightclub" class="w-full h-full object-cover">
            </div>
        </div>

        <!-- Persistent Content over Slider -->
        <div class="absolute inset-0 z-20 flex items-center">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                <div class="grid lg:grid-cols-2 gap-8 items-center">
                    <!-- Left: Text -->
                    <div id="hero-text-content" class="relative h-64">
                        <div class="slide-text active">
                            <div class="space-y-6">
                                <h1 class="text-5xl lg:text-7xl font-bold perspective-card">
                                    <span class="text-gradient-yellow text-shadow-yellow-lg">Welcome to POJ Music Club</span>
                                </h1>
                                <p class="text-2xl text-gray-300" style="text-shadow: 0 0 10px rgba(0, 0, 0, 0.8);">Where Passion Meets Melody</p>
                            </div>
                        </div>
                        <div class="slide-text">
                            <div class="space-y-6">
                                <h1 class="text-5xl lg:text-7xl font-bold perspective-card">
                                    <span class="text-gradient-yellow text-shadow-yellow-lg">Live Performances Every Week</span>
                                </h1>
                                <p class="text-2xl text-gray-300" style="text-shadow: 0 0 10px rgba(0, 0, 0, 0.8);">Experience Music Like Never Before</p>
                            </div>
                        </div>
                        <div class="slide-text">
                            <div class="space-y-6">
                                <h1 class="text-5xl lg:text-7xl font-bold perspective-card">
                                    <span class="text-gradient-yellow text-shadow-yellow-lg">Join Our Musical Family</span>
                                </h1>
                                <p class="text-2xl text-gray-300" style="text-shadow: 0 0 10px rgba(0, 0, 0, 0.8);">Exclusive Member Benefits Await</p>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Login Box -->
                    <div id="loginBox" class="glass-morphism p-8 rounded-2xl shadow-2xl" style="box-shadow: 0 20px 60px rgba(0, 0, 0, 0.8), 0 0 40px rgba(234, 179, 8, 0.2);">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 p-2 rounded-lg"><i class="fas fa-music text-black text-xl"></i></div>
                            <h3 class="text-2xl font-bold text-yellow-500" style="text-shadow: 0 0 20px rgba(234, 179, 8, 0.5);">Member Login</h3>
                        </div>
                        <form class="space-y-4" action="#" method="post">
                            @csrf
                            <div>
                                <label for="email" class="block text-sm font-medium mb-2">Email Address</label>
                                <input id="email" type="email" class="w-full px-4 py-3 bg-black/50 border border-emerald-700 rounded-lg focus:border-yellow-500 focus:outline-none focus:ring-2 focus:ring-yellow-500/50 transition-all" placeholder="your@email.com">
                            </div>
                            <div>
                                <label for="password" class="block text-sm font-medium mb-2">Password</label>
                                <input id="password" type="password" class="w-full px-4 py-3 bg-black/50 border border-emerald-700 rounded-lg focus:border-yellow-500 focus:outline-none focus:ring-2 focus:ring-yellow-500/50 transition-all" placeholder="••••••••">
                            </div>
                            <div class="flex items-center justify-between">
                                <label class="flex items-center">
                                    <input type="checkbox" class="w-4 h-4 rounded border-emerald-700 text-yellow-500 focus:ring-yellow-500">
                                    <span class="ml-2 text-sm">Remember me</span>
                                </label>
                                <a href="#" class="text-sm text-yellow-500 hover:text-yellow-400">Forgot Password?</a>
                            </div>
                            <button type="submit" class="w-full py-3 bg-gradient-to-r from-yellow-600 to-yellow-500 text-black font-bold rounded-lg hover:from-yellow-500 hover:to-yellow-400 transition-all transform hover:scale-105" style="box-shadow: 0 4px 15px rgba(234, 179, 8, 0.4);">Login</button>
                            <p class="text-center text-sm">Don't have an account?
                                <a href="#" class="text-yellow-500 hover:text-yellow-400 font-semibold">Register Now</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slider Controls -->
        <button id="prevSlide" aria-label="Previous slide" class="absolute left-4 top-1/2 -translate-y-1/2 z-30 p-3 bg-black/50 hover:bg-yellow-600 rounded-full transition-all transform hover:scale-110" style="box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);"><i class="fas fa-chevron-left text-2xl"></i></button>
        <button id="nextSlide" aria-label="Next slide" class="absolute right-4 top-1/2 -translate-y-1/2 z-30 p-3 bg-black/50 hover:bg-yellow-600 rounded-full transition-all transform hover:scale-110" style="box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);"><i class="fas fa-chevron-right text-2xl"></i></button>
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-30 flex space-x-2" id="sliderIndicators"></div>
    </section>

    {{-- Placeholder Sections (IDs optional here since navbar uses # only) --}}
    <section class="py-20 bg-gradient-to-b from-black to-emerald-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold mb-4 text-gradient-yellow text-shadow-yellow">About The Club</h2>
            <p class="text-gray-400 max-w-3xl mx-auto">
                POJ Music Club is a premium music community offering live performances, music education, and exclusive events.
            </p>
        </div>
    </section>

    <section class="relative py-20 bg-emerald-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl font-bold text-center mb-12 text-gradient-yellow text-shadow-yellow">Club Features</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="glass-morphism p-6 rounded-xl hover:border-yellow-500 transition-all perspective-card">
                    <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center mb-4 shadow-lg"><i class="fas fa-calendar text-black text-xl"></i></div>
                    <h3 class="text-xl font-bold text-yellow-500 mb-2">Exclusive Events</h3>
                    <p class="text-gray-400">Member-only workshops and jam sessions.</p>
                </div>
                <div class="glass-morphism p-6 rounded-xl hover:border-yellow-500 transition-all perspective-card">
                    <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center mb-4 shadow-lg"><i class="fas fa-award text-black text-xl"></i></div>
                    <h3 class="text-xl font-bold text-yellow-500 mb-2">Premium Benefits</h3>
                    <p class="text-gray-400">VIP access and priority booking.</p>
                </div>
                <div class="glass-morphism p-6 rounded-xl hover:border-yellow-500 transition-all perspective-card">
                    <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center mb-4 shadow-lg"><i class="fas fa-music text-black text-xl"></i></div>
                    <h3 class="text-xl font-bold text-yellow-500 mb-2">Live Performances</h3>
                    <p class="text-gray-400">Weekly shows with amazing artists.</p>
                </div>
                <div class="glass-morphism p-6 rounded-xl hover:border-yellow-500 transition-all perspective-card">
                    <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center mb-4 shadow-lg"><i class="fas fa-users text-black text-xl"></i></div>
                    <h3 class="text-xl font-bold text-yellow-500 mb-2">Music Community</h3>
                    <p class="text-gray-400">Connect with fellow music lovers.</p>
                </div>
            </div>
        </div>
    </section>

    

    <section class="relative py-20 bg-black">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl font-bold text-center mb-12 text-gradient-yellow text-shadow-yellow">Frequently Asked Questions</h2>
            <div class="space-y-4">
                <div class="glass-morphism rounded-xl overflow-hidden perspective-card">
                    <button class="faq-btn w-full px-6 py-4 flex items-center justify-between hover:bg-emerald-900/20 transition-all" aria-expanded="false">
                        <span class="text-lg font-semibold text-left">What is POJ Music Club?</span>
                        <i class="fas fa-chevron-down text-yellow-500 transition-transform"></i>
                    </button>
                    <div class="faq-content px-6 pb-4 text-gray-400 hidden">
                        POJ Music Club is a premium music community offering live performances, music education, and exclusive events for music enthusiasts of all levels.
                    </div>
                </div>
                <div class="glass-morphism rounded-xl overflow-hidden perspective-card">
                    <button class="faq-btn w-full px-6 py-4 flex items-center justify-between hover:bg-emerald-900/20 transition-all" aria-expanded="false">
                        <span class="text-lg font-semibold text-left">How do I become a member?</span>
                        <i class="fas fa-chevron-down text-yellow-500 transition-transform"></i>
                    </button>
                    <div class="faq-content px-6 pb-4 text-gray-400 hidden">
                        Click on 'Subscribe' or 'Buy Packages' to view membership options and complete registration.
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // === Three.js 3D Background ===
    let scene, camera, renderer, instruments = [];
    const container = document.getElementById('three-canvas');

    function initThree() {
        scene = new THREE.Scene();
        camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
        camera.position.z = 5;

        renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
        renderer.setPixelRatio(window.devicePixelRatio);
        renderer.setSize(window.innerWidth, window.innerHeight);
        container.appendChild(renderer.domElement);

        const ambientLight = new THREE.AmbientLight(0xffffff, 0.6);
        scene.add(ambientLight);
        const directionalLight = new THREE.DirectionalLight(0xfde047, 1);
        directionalLight.position.set(5, 5, 5);
        scene.add(directionalLight);
        const pointLight = new THREE.PointLight(0x10b981, 1.5, 100);
        pointLight.position.set(-5, -5, 2);
        scene.add(pointLight);

        const guitar = createRealisticGuitar();
        guitar.position.set(-3.5, 0.5, -2);
        instruments.push(guitar);
        scene.add(guitar);

        const drum = createRealisticDrum();
        drum.position.set(3, -1.5, 0);
        instruments.push(drum);
        scene.add(drum);

        const mic = createRealisticMicrophone();
        mic.position.set(4, 2, -1);
        instruments.push(mic);
        scene.add(mic);

        const note = createMusicNote();
        note.position.set(-2.5, -2.5, 1);
        instruments.push(note);
        scene.add(note);

        window.addEventListener('resize', onWindowResize, false);
        animate();
    }

    function createRealisticGuitar() {
        const group = new THREE.Group();
        const woodMaterial = new THREE.MeshStandardMaterial({ color: 0x8B4513, roughness: 0.6, metalness: 0.1 });
        const darkWoodMaterial = new THREE.MeshStandardMaterial({ color: 0x4A2511, roughness: 0.7 });
        // Body
        const bodyShape = new THREE.Shape();
        bodyShape.moveTo(0, -1);
        bodyShape.bezierCurveTo( -0.8, -1.2, -0.8, 0, -0.6, 0.5);
        bodyShape.bezierCurveTo( -0.5, 0.8, -0.2, 1, 0, 1.2);
        bodyShape.bezierCurveTo( 0.2, 1, 0.5, 0.8, 0.6, 0.5);
        bodyShape.bezierCurveTo( 0.8, 0, 0.8, -1.2, 0, -1);
        const extrudeSettings = { depth: 0.3, bevelEnabled: true, bevelThickness: 0.05, bevelSize: 0.05, bevelSegments: 2 };
        const bodyGeom = new THREE.ExtrudeGeometry(bodyShape, extrudeSettings);
        const body = new THREE.Mesh(bodyGeom, woodMaterial);
        body.scale.set(1.2, 1.2, 1.2);
        group.add(body);
        // Soundhole
        const soundholeGeom = new THREE.CircleGeometry(0.25, 32);
        const soundhole = new THREE.Mesh(soundholeGeom, new THREE.MeshBasicMaterial({ color: 0x000000 }));
        soundhole.position.set(0, 0.2, 0.19);
        body.add(soundhole);
        // Neck
        const neckGeom = new THREE.BoxGeometry(0.25, 2, 0.2);
        const neck = new THREE.Mesh(neckGeom, darkWoodMaterial);
        neck.position.y = 2.3;
        group.add(neck);
        // Headstock
        const headstockGeom = new THREE.BoxGeometry(0.35, 0.5, 0.2);
        const headstock = new THREE.Mesh(headstockGeom, darkWoodMaterial);
        headstock.position.y = 3.4;
        group.add(headstock);
        group.rotation.z = 0.3;
        return group;
    }

    function createRealisticDrum() {
        const group = new THREE.Group();
        const shellMaterial = new THREE.MeshStandardMaterial({ color: 0x047857, metalness: 0.4, roughness: 0.3 });
        const headMaterial = new THREE.MeshStandardMaterial({ color: 0xf0f0f0, roughness: 0.8 });
        const metalMaterial = new THREE.MeshStandardMaterial({ color: 0xcccccc, metalness: 0.9, roughness: 0.1 });
        const shellGeom = new THREE.CylinderGeometry(0.8, 0.8, 0.7, 48);
        const shell = new THREE.Mesh(shellGeom, shellMaterial);
        group.add(shell);
        const rimGeom = new THREE.TorusGeometry(0.8, 0.04, 16, 48);
        const topRim = new THREE.Mesh(rimGeom, metalMaterial);
        topRim.position.y = 0.35; topRim.rotation.x = Math.PI / 2;
        const bottomRim = new THREE.Mesh(rimGeom, metalMaterial);
        bottomRim.position.y = -0.35; bottomRim.rotation.x = Math.PI / 2;
        group.add(topRim, bottomRim);
        const headGeom = new THREE.CircleGeometry(0.8, 48);
        const topHead = new THREE.Mesh(headGeom, headMaterial);
        topHead.position.y = 0.35; topHead.rotation.x = -Math.PI / 2;
        group.add(topHead);
        for (let i = 0; i < 8; i++) {
            const lugGeom = new THREE.BoxGeometry(0.1, 0.5, 0.1);
            const lug = new THREE.Mesh(lugGeom, metalMaterial);
            const angle = (i / 8) * Math.PI * 2;
            lug.position.set(Math.cos(angle) * 0.85, 0, Math.sin(angle) * 0.85);
            lug.lookAt(0, 0, 0);
            group.add(lug);
        }
        group.rotation.x = 0.5; group.rotation.y = 0.5;
        return group;
    }

    function createRealisticMicrophone() {
        const group = new THREE.Group();
        const handleMaterial = new THREE.MeshStandardMaterial({ color: 0x4a4a4a, metalness: 0.8, roughness: 0.3 });
        const headMaterial = new THREE.MeshStandardMaterial({ color: 0xcccccc, metalness: 1.0, roughness: 0.2, wireframe: true });
        const innerHeadMaterial = new THREE.MeshStandardMaterial({ color: 0x333333, roughness: 0.5 });
        const headGeom = new THREE.SphereGeometry(0.4, 16, 12);
        const head = new THREE.Mesh(headGeom, headMaterial);
        const innerHead = new THREE.Mesh(new THREE.SphereGeometry(0.38, 16, 12), innerHeadMaterial);
        head.add(innerHead); head.position.y = 0.9; group.add(head);
        const handleGeom = new THREE.CylinderGeometry(0.2, 0.15, 1.8, 32);
        const handle = new THREE.Mesh(handleGeom, handleMaterial); group.add(handle);
        group.rotation.z = -0.4;
        return group;
    }

    function createMusicNote() {
        const group = new THREE.Group();
        const material = new THREE.MeshStandardMaterial({ color: 0xfde047, emissive: 0xeab308, emissiveIntensity: 0.4, metalness: 0.5, roughness: 0.3 });
        const headGeom = new THREE.SphereGeometry(0.25, 16, 16);
        const head = new THREE.Mesh(headGeom, material); group.add(head);
        const stemGeom = new THREE.CylinderGeometry(0.05, 0.05, 1.2, 8);
        const stem = new THREE.Mesh(stemGeom, material); stem.position.set(0.2, 0.5, 0); group.add(stem);
        const flagGeom = new THREE.TorusGeometry(0.3, 0.05, 8, 24, Math.PI / 2);
        const flag = new THREE.Mesh(flagGeom, material);
        flag.position.set(0.2, 1.1, 0); flag.rotation.z = Math.PI / 1.5; group.add(flag);
        return group;
    }

    function onWindowResize() {
        camera.aspect = window.innerWidth / window.innerHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(window.innerWidth, window.innerHeight);
    }

    function animate() {
        requestAnimationFrame(animate);
        const time = Date.now() * 0.0005;
        instruments.forEach((inst, i) => {
            inst.rotation.x += 0.0005 * (i % 2 === 0 ? 1 : -1);
            inst.rotation.y += 0.001;
            inst.position.y += Math.sin(time + i) * 0.003;
        });
        renderer.render(scene, camera);
    }

    initThree();

    // === Slider ===
    let currentSlide = 0;
    const slides = document.querySelectorAll('.slide');
    const slideTexts = document.querySelectorAll('.slide-text');
    const indicatorsContainer = document.getElementById('sliderIndicators');
    const totalSlides = slides.length;

    function createIndicators() {
        for (let i = 0; i < totalSlides; i++) {
            const button = document.createElement('button');
            button.classList.add('indicator','transition-all');
            button.dataset.slideTo = i;
            indicatorsContainer.appendChild(button);
        }
    }
    function updateIndicators() {
        const indicators = indicatorsContainer.querySelectorAll('.indicator');
        indicators.forEach((indicator, i) => {
            const isActive = i === currentSlide;
            indicator.classList.toggle('active', isActive);
            indicator.classList.toggle('w-8', isActive);
            indicator.classList.toggle('bg-yellow-500', isActive);
            indicator.classList.toggle('w-3', !isActive);
            indicator.classList.toggle('h-3', true);
            indicator.classList.toggle('rounded-full', true);
            indicator.classList.toggle('bg-white/50', !isActive);
            indicator.style.boxShadow = isActive ? '0 0 10px rgba(234, 179, 8, 0.8)' : '';
            indicator.setAttribute('aria-label', `Go to slide ${i + 1}`);
        });
    }
    function showSlide(index) {
        currentSlide = (index + totalSlides) % totalSlides;
        slides.forEach((slide, i) => slide.classList.toggle('active', i === currentSlide));
        slideTexts.forEach((text, i) => text.classList.toggle('active', i === currentSlide));
        updateIndicators();
    }
    createIndicators(); showSlide(0);
    document.getElementById('nextSlide').addEventListener('click', () => showSlide(currentSlide + 1));
    document.getElementById('prevSlide').addEventListener('click', () => showSlide(currentSlide - 1));
    indicatorsContainer.addEventListener('click', (e) => {
        if (e.target.matches('.indicator')) showSlide(parseInt(e.target.dataset.slideTo, 10));
    });
    setInterval(() => showSlide(currentSlide + 1), 5000);

    // slide-text CSS (runtime inject)
    const style = document.createElement('style');
    style.innerHTML = `.slide-text{position:absolute;opacity:0;transition:opacity .5s ease-in-out}.slide-text.active{opacity:1}`;
    document.head.appendChild(style);

    // === FAQ Accordion ===
    document.querySelectorAll('.faq-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const content = btn.nextElementSibling;
            const icon = btn.querySelector('i');
            const isExpanded = btn.getAttribute('aria-expanded') === 'true';
            content.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
            btn.setAttribute('aria-expanded', !isExpanded);
        });
    });

    // === Mouse-Tilt Effect for Login Box ===
    const loginBox = document.getElementById('loginBox');
    if (loginBox) {
        const updateTilt = (e) => {
            const rect = loginBox.getBoundingClientRect();
            const centerX = rect.left + rect.width / 2;
            const centerY = rect.top + rect.height / 2;
            const rotateY = (e.clientX - centerX) / (rect.width / 2) * 5;
            const rotateX = (e.clientY - centerY) / (rect.height / 2) * -5;
            loginBox.style.transform = `rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.02)`;
        };
        loginBox.addEventListener('mousemove', (e) => { window.requestAnimationFrame(() => updateTilt(e)); });
        loginBox.addEventListener('mouseleave', () => { loginBox.style.transform = 'rotateX(0deg) rotateY(0deg) scale(1)'; });
    }
});
</script>
@endpush
