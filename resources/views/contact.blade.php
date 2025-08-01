@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-cover bg-no-repeat flex items-center justify-center px-4 pt-24 pb-24" style="background-image: url('{{ asset('images/contactus.png') }}')">
    <div class="bg-white bg-opacity-80 rounded-2xl shadow-2xl max-w-3xl w-full p-10" data-aos="fade-up" data-aos-delay="100">
        <h1 class="text-4xl font-extrabold text-black text-center mb-6" data-aos="zoom-in">Contact Us</h1>
        <p class="text-center text-gray-700 mb-10" data-aos="fade-up" data-aos-delay="200">
            We’re here to assist you. Whether you're buying, selling, or just curious,<br>feel free to reach out to us through the following channels.
        </p>

        <div class="space-y-6 text-gray-800 text-lg px-4" data-aos="fade-up" data-aos-delay="300">
            <p class="flex items-center gap-2"><span class="text-xl">📞</span><strong>Hotline:</strong> 0766890720</p>
            <p class="flex items-center gap-2"><span class="text-xl">☎</span><strong>Telephone:</strong> 0112299892</p>
            <p class="flex items-center gap-2"><span class="text-xl">📧</span><strong>Email:</strong>
                <a href="mailto:irrealestates@gmail.com" class="text-indigo-600 hover:underline">irrealestates@gmail.com</a>
            </p>
            <p class="flex items-center gap-2"><span class="text-xl">📍</span><strong>Office Address:</strong> 123 Main Street, Colombo 07, Sri Lanka</p>
            <p class="flex items-center gap-2"><span class="text-xl">🕒</span><strong>Working Hours:</strong> Monday - Friday, 9:00 AM - 6:00 PM</p>
        </div>

        <div class="mt-10 text-center" data-aos="fade-up" data-aos-delay="400">
            <p class="text-gray-600 text-base">
                You can also reach us via our
                <a href="/register" class="text-indigo-700 font-medium hover:underline hover:text-indigo-900 transition">online platform</a> —
                create an account and start exploring!
            </p>
        </div>
    </div>
</div>
@endsection
