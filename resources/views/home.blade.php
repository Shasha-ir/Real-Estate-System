@extends('layouts.app')

@section('content')
    <style>
        .zoom-bg {
            animation: zoomInBackground 20s ease-in-out infinite alternate;
        }

        @keyframes zoomInBackground {
            from {
                background-size: 100%;
            }

            to {
                background-size: 110%;
            }
        }
    </style>

    <!-- Hero Section with Zooming Background -->
    <div class="min-h-screen bg-center bg-no-repeat bg-cover zoom-bg flex flex-col items-center justify-center pt-24 pb-24 px-4"
        style="background-image: url('{{ asset('images/background.png') }}')">
        <div class="bg-white bg-opacity-75 rounded-2xl shadow-2xl p-12 max-w-4xl w-full text-center" data-aos="zoom-in">
            <h1 class="text-4xl font-extrabold text-black mb-6" data-aos="fade-up" data-aos-delay="100">Welcome to IR Real
                Estates</h1>
            <p class="text-xl text-gray-700 mb-8" data-aos="fade-up" data-aos-delay="200">Where Property Management Meets
                Excellence</p>
            <div class="flex justify-center space-x-6" data-aos="fade-up" data-aos-delay="300">
                <a href="{{ route('register') }}"
                    class="px-7 py-3 bg-indigo-600 text-white rounded-xl text-lg font-semibold hover:bg-indigo-700 transition">Get
                    Started</a>
                <a href="{{ route('login') }}"
                    class="px-7 py-3 bg-white text-indigo-700 border border-indigo-600 rounded-xl text-lg font-semibold hover:bg-gray-100 transition">Login</a>
            </div>
        </div>

        <!-- Scroll Down Arrow (linked to #about section) -->
        <a href="#about" class="absolute bottom-12 animate-bounce">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white opacity-100 hover:opacity-100 transition"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </a>


    </div>

    <!-- About Us Section -->
    <section id="about" class="py-20 bg-gray-100">
        <div class="max-w-6xl mx-auto px-6 text-center" data-aos="fade-up">
            <h2 class="text-3xl font-bold text-indigo-700 mb-6">About Us</h2>
            <p class="text-lg text-gray-700 leading-relaxed">
                IR Real Estates is your trusted partner in property management, dedicated to simplifying the real estate
                journey for buyers and sellers across the nation.
                We believe in transparency, professionalism, and secure transactions. Whether you’re listing a property or
                finding your dream home, we’re here to make it seamless.
            </p>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold text-indigo-700 mb-12" data-aos="fade-up">Why Choose IR Real Estates?</h2>

            <div class="grid md:grid-cols-3 gap-10">
                <div class="bg-gray-100 p-8 rounded-xl shadow-md" data-aos="fade-up" data-aos-delay="100">
                    <h3 class="text-xl font-semibold text-black mb-4">🔒 Secure Platform</h3>
                    <p class="text-gray-700">We prioritize secure payment and user verification for a safe property
                        experience.</p>
                </div>

                <div class="bg-gray-100 p-8 rounded-xl shadow-md" data-aos="fade-up" data-aos-delay="200">
                    <h3 class="text-xl font-semibold text-black mb-4">💼 Trusted Sellers</h3>
                    <p class="text-gray-700">Browse verified sellers with proven track records and property histories.</p>
                </div>

                <div class="bg-gray-100 p-8 rounded-xl shadow-md" data-aos="fade-up" data-aos-delay="300">
                    <h3 class="text-xl font-semibold text-black mb-4">📄 Instant Receipts</h3>
                    <p class="text-gray-700">Get PDF receipts for every transaction and reservation made through our
                        platform.</p>
                </div>
            </div>
        </div>
    </section>
    <!-- Featured Properties Section -->
    <section class="py-20 bg-gray-100">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold text-indigo-700 mb-12" data-aos="fade-up">Featured Properties</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                @for ($i = 1; $i <= 5; $i++)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-xl transform hover:-translate-y-1 transition duration-300"
                        data-aos="fade-up" data-aos-delay="{{ $i * 100 }}">
                        <img src="{{ asset('images/image' . $i . '.jpg') }}" alt="Property {{ $i }}"
                            class="w-full h-48 object-cover rounded-t-lg">
                        <div class="p-4 text-left">
                            <h3 class="text-xl font-semibold text-black mb-2">Property #{{ $i }}</h3>
                            <p class="text-sm text-gray-600 mb-2">3 Bed • 2 Bath • 1800 sqft</p>
                            <p class="text-indigo-700 font-bold mb-3">Rs. {{ number_format(1500000 + $i * 100000) }}</p>
                            <a href="{{ url('/properties') }}"
                                class="inline-block text-sm font-semibold text-indigo-600 hover:underline">View Details →</a>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold text-indigo-700 mb-12" data-aos="fade-up">Our Impact</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-10">
                <div class="text-center" data-aos="fade-up" data-aos-delay="100">
                    <p class="text-4xl font-bold text-black">xxx+</p>
                    <p class="text-gray-600 mt-2">Properties Listed</p>
                </div>
                <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                    <p class="text-4xl font-bold text-black">xx+</p>
                    <p class="text-gray-600 mt-2">Successful Reservations</p>
                </div>
                <div class="text-center" data-aos="fade-up" data-aos-delay="300">
                    <p class="text-4xl font-bold text-black">xx+</p>
                    <p class="text-gray-600 mt-2">Verified Sellers</p>
                </div>
                <div class="text-center" data-aos="fade-up" data-aos-delay="400">
                    <p class="text-4xl font-bold text-black">xx%</p>
                    <p class="text-gray-600 mt-2">Satisfaction Rate</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonial Carousel -->
    <section class="py-20 bg-gray-100">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold text-indigo-700 mb-12" data-aos="fade-up">What Our Clients Say</h2>

            <div class="swiper" data-aos="fade-up">
                <div class="swiper-wrapper">
                    <!-- Testimonial 1 -->
                    <div class="swiper-slide bg-white p-6 rounded-xl shadow-md">
                        <p class="text-gray-700 italic mb-4">"I found my dream home within days of using IR Real Estates.
                            The process was smooth and professional!"</p>
                        <p class="text-sm font-semibold text-indigo-600">– Shasha Isuri</p>
                    </div>

                    <!-- Testimonial 2 -->
                    <div class="swiper-slide bg-white p-6 rounded-xl shadow-md">
                        <p class="text-gray-700 italic mb-4">"As a seller, I loved the quick listing setup and instant
                            receipt generation. 10/10!"</p>
                        <p class="text-sm font-semibold text-indigo-600">– Bellamy Blake</p>
                    </div>

                    <!-- Testimonial 3 -->
                    <div class="swiper-slide bg-white p-6 rounded-xl shadow-md">
                        <p class="text-gray-700 italic mb-4">"Great platform, excellent support, and smooth user experience.
                            Highly recommend!"</p>
                        <p class="text-sm font-semibold text-indigo-600">– Gojou Satoru</p>
                    </div>
                </div>

                <!-- Pagination Dots -->
                <div class="swiper-pagination mt-6"></div>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="bg-indigo-500 text-white py-20 text-center">
        <div class="max-w-4xl mx-auto px-6" data-aos="fade-up">
            <h2 class="text-3xl font-bold mb-4">Ready to find your dream property?</h2>
            <p class="text-lg mb-6">Join IR Real Estates today as a buyer or seller and experience a seamless, trusted
                platform.</p>
            <div class="flex justify-center gap-6">
                <a href="{{ route('register') }}"
                    class="bg-white text-indigo-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">Get
                    Started</a>
                <a href="{{ url('/properties') }}"
                    class="border border-white px-6 py-3 rounded-lg font-semibold hover:bg-white hover:text-indigo-700 transition">Browse
                    Properties</a>
            </div>
        </div>
    </section>




@endsection