@extends('appsita')

@section('title', 'Instituto de Capacitaciones Alimentarias')

@section('content')
    @php
        $hasResults = isset($records);
    @endphp

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* ANIMACIONES FALTANTES EN LA CONFIGURACIÓN DE APPSITA */
        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(50px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeInUp 0.8s ease-out;
        }

        .animate-fade-in-delay {
            animation: fadeInUp 0.8s ease-out 0.2s backwards;
        }

        .animate-fade-in-delay-2 {
            animation: fadeInUp 0.8s ease-out 0.4s backwards;
        }

        .animate-fade-in-delay-3 {
            animation: fadeInUp 0.8s ease-out 0.6s backwards;
        }

        /* CSS DEL CARRUSEL Y PÁGINA */
        body {
            background-color: #faf8f7;
            color: #4d4341;
        }

        .carousel-container {
            position: relative;
            width: 100%;
            height: 100vh;
        }

        .carousel-slide {
            display: none;
        }

        .carousel-slide.active {
            display: block;
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideInUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .carousel-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(77, 67, 65, 0.7);
            color: white;
            border: none;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            z-index: 10;
            backdrop-filter: blur(5px);
        }

        .carousel-btn:hover {
            background: rgba(77, 67, 65, 0.95);
            transform: translateY(-50%) scale(1.1);
        }

        .carousel-prev {
            left: 20px;
        }

        .carousel-next {
            right: 20px;
        }

        .carousel-indicators {
            position: absolute;
            bottom: 120px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 12px;
            z-index: 10;
        }

        .indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid rgba(255, 255, 255, 0.8);
        }

        .indicator:hover {
            background: rgba(255, 255, 255, 0.8);
            transform: scale(1.2);
        }

        .indicator.active {
            background: white;
            width: 40px;
            border-radius: 6px;
        }

        .carousel-slide img {
            transition: transform 8s ease-out;
            filter: brightness(1.1);
        }

        .carousel-slide.active img {
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .carousel-btn {
                width: 45px;
                height: 45px;
                font-size: 18px;
            }

            .carousel-prev {
                left: 10px;
            }

            .carousel-next {
                right: 10px;
            }

            .carousel-indicators {
                bottom: 180px;
            }
        }
    </style>

    <!-- Navigation -->
    <nav class="bg-white shadow-lg fixed w-full z-[100] top-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="flex justify-between items-center h-16 md:h-20">
                <div class="flex items-center shrink">
                    <a href="{{ route('login') }}" class="shrink-0" title="Acceso Administrativo">
                        <img src="{{ asset('InnovaFood_Logo.png') }}" alt="Logo" class="w-10 h-10 md:w-16 md:h-16 mr-2 md:mr-3 object-contain hover:scale-105 transition-transform duration-300">
                    </a>
                    <div class="text-base sm:text-xl md:text-2xl font-black text-primary tracking-tight leading-tight truncate">{{ config('app.name', 'Innova Food G.C') }}</div>
                </div>
                <div class="flex items-center space-x-3 md:space-x-6 shrink-0 ml-2">
                    @auth
                        <a href="#buscador" class="text-sm md:text-lg font-bold text-gray-500 hidden sm:block hover:text-primary transition duration-300">Registro institucional</a>
                        <a href="{{ route('clients.index') }}"
                            class="text-white bg-primary hover:bg-primary-hover px-3 py-1.5 md:px-4 md:py-2 text-xs sm:text-sm md:text-base rounded-xl md:rounded-lg font-bold transition duration-300">
                            <span class="hidden sm:inline">Administrador</span>
                            <span class="sm:hidden">Admin</span>
                        </a>
                    @else
                        <a href="#buscador" class="text-sm md:text-lg font-bold text-primary hover:text-primary-hover transition duration-300 text-right leading-tight max-w-[100px] sm:max-w-none">
                            Registro<br class="sm:hidden"> institucional
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Carousel Section -->
    <section id="inicio" class="pt-0 relative">
        <div class="carousel-container relative overflow-hidden">
            <!-- Slide 1 -->
            <div class="carousel-slide active">
                <div class="relative h-screen">
                    <img src="{{ asset('images/ingenieria.jpg') }}" alt="Fondo" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-br from-[#4d4341]/80 via-[#4d4341]/60 to-transparent"></div>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pt-10">
                        <div class="text-center text-white px-4 max-w-5xl mb-8">
                            <h1
                                class="text-5xl md:text-7xl font-black mb-6 animate-fade-in drop-shadow-[0_2px_4px_rgba(0,0,0,0.5)]">
                                Registro Institucional
                            </h1>
                            <p class="text-xl md:text-3xl font-bold mb-4 animate-fade-in-delay text-white/90">
                                Valida las credenciales y certificaciones
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="carousel-slide">
                <div class="relative h-screen">
                    <img src="{{ asset('images/ingenieria2.jpg') }}" alt="Fondo" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-tr from-[#3a3230]/85 via-[#4d4341]/60 to-black/40"></div>
                    <div class="absolute inset-0 flex items-center justify-center pt-10">
                        <div class="text-center text-white px-4 max-w-5xl">
                            <h2
                                class="text-5xl md:text-7xl font-black mb-6 animate-fade-in drop-shadow-[0_2px_4px_rgba(0,0,0,0.5)]">
                                Calidad e Inocuidad</h2>
                            <p class="text-xl md:text-3xl font-bold animate-fade-in-delay text-white/90">
                                Certificaciones garantizadas en el sector alimentario
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 3 -->
            <div class="carousel-slide">
                <div class="relative h-screen">
                    <img src="{{ asset('images/ingenieroa3.jpg') }}" alt="Fondo" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-bl from-transparent via-[#4d4341]/80 to-[#3a3230]/90"></div>
                    <div class="absolute inset-0 flex items-center justify-center pt-10">
                        <div class="text-center text-white px-4 max-w-5xl">
                            <h2
                                class="text-5xl md:text-7xl font-black mb-6 animate-fade-in drop-shadow-[0_2px_4px_rgba(0,0,0,0.5)]">
                                Capacitación Profesional</h2>
                            <p class="text-xl md:text-3xl font-bold animate-fade-in-delay text-white/90">
                                Profesionales formados con los más altos estándares
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Controles del carrusel -->
            <button class="carousel-btn carousel-prev" onclick="carouselNav(-1)"><i
                    class="fas fa-chevron-left"></i></button>
            <button class="carousel-btn carousel-next" onclick="carouselNav(1)"><i
                    class="fas fa-chevron-right"></i></button>

            <!-- Indicadores -->
            <div class="carousel-indicators">
                <span class="indicator active" onclick="currentSlide(0)"></span>
                <span class="indicator" onclick="currentSlide(1)"></span>
                <span class="indicator" onclick="currentSlide(2)"></span>
            </div>
        </div>

    </section>

    <!-- SECCIÓN DE BÚSQUEDA (FLUJO NORMAL) -->
    <section id="buscador" class="py-16 bg-gray-50 relative z-30 flex flex-col items-center justify-center">
        <div class="w-full max-w-3xl px-6">
            <!-- Título del buscador según requerimiento del usuario -->
            <h3 class="text-center text-primary font-black text-2xl md:text-4xl mb-10 drop-shadow-sm">
                Registro nacional e internacional
            </h3>

            <form method="POST" action="{{ route('lookup.search') }}#resultados"
                class="group flex bg-white/95 backdrop-blur-md rounded-full shadow-[0_20px_50px_rgba(0,0,0,0.15)] border-2 border-white/40 overflow-hidden transition-all duration-500 focus-within:ring-4 focus-within:ring-primary/30 focus-within:border-primary/50 hover:shadow-[0_30px_60px_rgba(0,0,0,0.2)] hover:scale-[1.01]">
                @csrf
                <div class="flex-1 flex items-center gap-4 px-8 py-2">
                    <span class="iconify text-primary/60 text-2xl group-focus-within:text-primary transition-colors duration-300" data-icon="line-md:account-small"></span>
                    <input id="cedula" type="number" name="cedula" value="{{ old('cedula', $cedula ?? '') }}"
                        placeholder="Ingresa tu cedula..." maxlength="20" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                        class="w-full py-5 text-base md:text-lg font-semibold text-gray-800 focus:outline-none bg-transparent placeholder-gray-400 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                </div>
                <button type="submit"
                    class="bg-gradient-to-r from-primary to-primary-hover text-white font-bold px-8 md:px-12 my-2 mr-2 rounded-full transition-transform duration-300 flex items-center gap-3 hover:shadow-lg transform hover:-translate-y-0.5 active:translate-y-0">
                    <span class="iconify text-xl" data-icon="line-md:search"></span>
                    <span class="hidden md:inline whitespace-nowrap">Consultar Credencial</span>
                </button>
            </form>
            @error('cedula')
                <div class="text-center mt-4">
                    <p
                        class="text-red-500 text-sm font-bold flex items-center justify-center gap-1 bg-white/90 backdrop-blur inline-flex px-4 py-2 rounded-xl shadow-sm border border-red-100">
                        <span class="iconify" data-icon="line-md:alert-circle"></span> {{ $message }}
                    </p>
                </div>
            @enderror
        </div>
    </section>

    <!-- Resultados de Búsqueda -->
    @if ($hasResults || session('search_attempted'))
        <section id="resultados" class="pt-12 pb-16 bg-[#faf8f7] relative z-20 min-h-[50vh]">
            <div class="max-w-2xl w-full mx-auto px-6">
                @if ($records->isEmpty())
                    <div class="text-center py-16 opacity-70 bg-white rounded-3xl shadow-md border border-gray-100">
                        <div class="bg-gray-50 w-16 h-16 mx-auto rounded-2xl flex items-center justify-center mb-4">
                            <span class="iconify text-3xl text-gray-300" data-icon="line-md:search-minus"></span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-700">No hay registros</h3>
                        <p class="text-sm mt-1">No encontramos cursos vinculados a: <span
                                class="font-mono font-bold text-primary">{{ $cedula }}</span></p>
                    </div>
                @else
                    {{-- Profile Card --}}
                    <div class="text-center mb-10">
                        <div
                            class="inline-flex flex-col items-center p-6 bg-white rounded-3xl shadow-sm border border-gray-100 w-full">
                            <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mb-4">
                                <span class="iconify text-3xl text-primary" data-icon="line-md:account"></span>
                            </div>
                            <h2 class="text-2xl font-black text-gray-800 tracking-tight leading-none mb-2">{{ $person->full_name }}
                            </h2>
                            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">Documento:
                                {{ $person->id_card }}</p>
                            <div class="bg-primary/5 px-4 py-1.5 rounded-full border border-primary/10">
                                <span class="text-xs font-black text-primary">{{ $records->count() }}
                                    {{ $records->count() === 1 ? 'Registro Encontrado' : 'Registros Encontrados' }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Course Timeline --}}
                    <div class="relative max-w-lg mx-auto">
                        {{-- Connecting Line --}}
                        <div
                            class="absolute left-6 top-6 bottom-6 w-0.5 bg-gradient-to-b from-primary/30 via-primary/5 to-transparent hidden sm:block">
                        </div>

                        @foreach ($records as $record)
                            @php
                                $expirationDate = \Carbon\Carbon::parse($record->finished_at)->addYear();
                                $isExpired = $expirationDate->isPast();
                                $styles = $isExpired ? [
                                    'bg' => 'bg-orange-500 shadow-orange-200',
                                    'icon' => 'line-md:alert-circle',
                                    'text' => 'text-orange-600',
                                    'label' => 'Retomar Certificación',
                                    'border' => 'border-orange-500/20'
                                ] : [
                                    'bg' => 'bg-green-500 shadow-green-200',
                                    'icon' => 'line-md:check-all',
                                    'text' => 'text-green-600',
                                    'label' => 'Vigente',
                                    'border' => 'border-green-500/20'
                                ];
                            @endphp
                            <div class="relative flex sm:gap-6 items-start mb-8 last:mb-0 group cursor-default">
                                {{-- Timeline Node --}}
                                <div
                                    class="relative z-10 w-14 h-14 rounded-2xl hidden sm:flex items-center justify-center shrink-0 border-4 border-[#faf8f7] transition-all duration-500 group-hover:scale-110 group-hover:rotate-3 shadow-md {{ $styles['bg'] }} text-white">
                                    <span class="iconify text-2xl" data-icon="{{ $styles['icon'] }}"></span>
                                </div>

                                {{-- Card Info --}}
                                <div
                                    class="flex-1 bg-white rounded-3xl border {{ $styles['border'] }} shadow-[0_10px_30px_rgba(0,0,0,0.04)] hover:shadow-[0_20px_50px_rgba(0,0,0,0.12)] transition-all duration-500 flex flex-col overflow-hidden transform group-hover:-translate-y-2 group-hover:border-primary/20">
                                    <div class="p-8 flex-1 relative overflow-hidden">
                                        {{-- Decoración sutil de fondo en la tarjeta web --}}
                                        <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full bg-gradient-to-br from-gray-50 to-gray-100 opacity-50 transform group-hover:scale-150 transition-transform duration-700"></div>
                                        
                                        <div class="relative z-10">
                                            <span
                                                class="text-xs font-extrabold text-primary/60 uppercase tracking-widest flex items-center gap-1.5 mb-2 align-middle transform transition-transform duration-500 group-hover:translate-x-1">
                                                <span class="iconify text-sm" data-icon="line-md:document-list"></span> Certificación de Curso
                                            </span>
                                            <h4
                                                class="font-black text-gray-900 text-xl md:text-2xl leading-tight group-hover:text-primary transition-colors duration-300">
                                                {{ $record->course_name }}
                                            </h4>
                                        </div>
                                    </div>

                                    <div class="px-8 py-5 border-t border-gray-100/50 bg-gradient-to-b from-gray-50/50 to-gray-50 flex flex-col gap-3 group-hover:bg-gray-50 transition-colors duration-500">
                                        <div class="flex justify-between items-center text-sm">
                                            <span class="font-bold text-gray-500">Fecha de finalización:</span>
                                            <span
                                                class="font-black text-gray-800 bg-white px-3 py-1 rounded-lg border border-gray-100 shadow-sm">{{ \Carbon\Carbon::parse($record->finished_at)->format('d/m/Y') }}</span>
                                        </div>
                                        <div class="flex justify-between items-center text-sm">
                                            <span class="font-bold text-gray-500">Curso válido hasta:</span>
                                            <span class="font-black {{ $styles['text'] }} bg-white px-3 py-1 rounded-lg border {{ $styles['border'] }} shadow-sm">{{ $expirationDate->format('d/m/Y') }}</span>
                                        </div>
                                        @if($isExpired)
                                            <div class="mt-4 text-center">
                                                <p
                                                    class="text-xs font-bold text-orange-600 uppercase tracking-widest flex items-center justify-center gap-1.5 bg-orange-50 py-2.5 rounded-xl border border-orange-100/50 animate-pulse">
                                                    <span class="iconify text-sm" data-icon="line-md:alert"></span> Debes Retomar la Certificación
                                                </p>
                                            </div>
                                        @else
                                            <div class="mt-4 text-center">
                                                <span class="inline-flex text-[11px] font-black uppercase tracking-wider text-green-600 bg-green-50 px-4 py-2 rounded-xl border border-green-100/50">
                                                    <span class="iconify text-sm mr-1.5" data-icon="line-md:check-all"></span> Certificado Vigente
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    @else
        <div class="pt-32 pb-16 bg-[#faf8f7] min-h-[30vh]">
            <!-- Espacio vacío en caso de no haber búsqueda, para que el Hero no colisione con el fin de la página -->
        </div>
    @endif

    <script>
        // JS INTEGRADO PARA GARANTIZAR QUE EL CARRUSEL FUNCIONE
        let currentSlideIndex = 0;
        let carouselInterval;

        function showSlide(index) {
            const slides = document.querySelectorAll('.carousel-slide');
            const indicators = document.querySelectorAll('.indicator');
            if (slides.length === 0) return;

            if (index >= slides.length) currentSlideIndex = 0;
            else if (index < 0) currentSlideIndex = slides.length - 1;
            else currentSlideIndex = index;

            slides.forEach(s => s.classList.remove('active'));
            indicators.forEach(i => i.classList.remove('active'));

            slides[currentSlideIndex].classList.add('active');
            if (indicators.length > 0) indicators[currentSlideIndex].classList.add('active');
        }

        function carouselNav(direction) {
            showSlide(currentSlideIndex + direction);
            resetCarouselTimer();
        }

        function currentSlide(index) {
            showSlide(index);
            resetCarouselTimer();
        }

        function autoSlide() {
            showSlide(currentSlideIndex + 1);
        }

        function startCarousel() {
            carouselInterval = setInterval(autoSlide, 6000); // 6 segundos
        }

        function resetCarouselTimer() {
            clearInterval(carouselInterval);
            startCarousel();
        }

        document.addEventListener('DOMContentLoaded', function () {
            startCarousel();

            // Si hay resultados, hacer scroll suave a la sección
            if (window.location.hash === '#resultados' || document.getElementById('resultados')) {
                setTimeout(function () {
                    const el = document.getElementById('resultados');
                    if (el) {
                        const y = el.getBoundingClientRect().top + window.scrollY - 100;
                        window.scrollTo({ top: y, behavior: 'smooth' });
                    }
                }, 300);
            }

            const carouselContainer = document.querySelector('.carousel-container');
            if (carouselContainer) {
                carouselContainer.addEventListener('mouseenter', () => clearInterval(carouselInterval));
                carouselContainer.addEventListener('mouseleave', () => startCarousel());
            }
        });
    </script>

    <!-- Footer -->
    <footer class="bg-[#4d4341] text-white py-12">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">Innova Food G.C</h3>
                    <p class="text-gray-300 mb-4">Instituto de capacitaciones profesionales especializado en el sector alimentario.</p>
                    <img src="{{ asset('InnovaFood_Logo.png') }}" alt="Logo" class="w-20 h-20 object-contain bg-white rounded-lg p-2">
                </div>

               

                <div>
                    <h4 class="text-lg font-bold mb-4">Servicios</h4>
                    <ul class="space-y-2">
                        <li>Capacitaciones</li>
                        <li>Consultoría</li>
                        <li>Certificaciones</li>
                        <li>Laboratorio</li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-lg font-bold mb-4">Contacto</h4>
                    <ul class="space-y-2 text-gray-300">
                        <li class="flex items-center"><span class="iconify mr-2 text-lg" data-icon="line-md:phone"></span>+593 99 842 6977</li>
                        <li class="flex items-center"><span class="iconify mr-2 text-lg" data-icon="line-md:email"></span>innovafoodgc21@gmail.com</li>
                        <li class="flex items-center"><span class="iconify mr-2 text-lg" data-icon="carbon:location"></span>Ambato - Ecuador</li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-600 mt-8 pt-8 text-center text-sm">
                <p class="text-gray-300">&copy; 2025 Innova Food G.C. Todos los derechos reservados. | Hacerlo tú mismo es mejor </p>
            </div>
        </div>
    </footer>

    <!-- Botón flotante de WhatsApp -->
    <a href="https://wa.me/593998426977?text=Hola%20Innova%20Food%20G.C.,%20estoy%20interesado%20en%20obtener%20m%C3%A1s%20informaci%C3%B3n%20sobre%20sus%20servicios%20y%20capacitaciones." target="_blank" class="fixed bottom-6 right-6 bg-green-500 hover:bg-green-600 text-white rounded-full p-4 shadow-lg hover:shadow-xl transition-all duration-300 z-50 group flex items-center justify-center">
        <span class="iconify text-3xl" data-icon="ri:whatsapp-line"></span>
        <span class="hidden group-hover:inline-block ml-2 font-semibold">Contáctanos</span>
    </a>
@endsection