@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-cover bg-no-repeat flex items-center justify-center px-4" style="background-image: url('{{ asset('images/contactus.png') }}')">

    <div class="bg-white bg-opacity-65  rounded-xl shadow-lg max-w-3xl w-full p-10">
        <h1 class="text-4xl font-bold text-black-700 text-center mb-6">Contact Us</h1>
        <p class="text-center text-gray-700 mb-10">We’re here to assist you. Whether you're buying, selling, or just curious, feel free to reach out to us through the following channels.</p>

        <div class="space-y-6 text-gray-800 text-lg">
            <p><strong>📞 Hotline:</strong> 0766890720</p>
            <p><strong>☎ Telephone:</strong> 0112299892</p>
            <p><strong>📧 Email:</strong> <a href="mailto:irrealestates@gmail.com" class="text-indigo-600 hover:underline">irrealestates@gmail.com</a></p>
            <p><strong>📍 Office Address:</strong> 123 Main Street, Colombo 07, Sri Lanka</p>
            <p><strong>🕒 Working Hours:</strong> Monday - Friday, 9:00 AM - 6:00 PM</p>
        </div>

        <div class="mt-10 text-center">
            <p class="text-gray-600">You can also reach us via our <a href="/register" class="text-indigo-600 hover:underline">online platform</a> — create an account and start exploring!</p>
        </div>
    </div>
</div>
@endsection
