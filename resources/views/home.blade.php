@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-cover flex flex-col items-center justify-center pt-24 pb-24 px-4" style="background-image: url('{{ asset('images/background.png') }}')">
        <div class="bg-white bg-opacity-75 rounded-2xl shadow-2xl p-12 max-w-4xl w-full text-center">
            <h1 class="text-4xl font-extrabold text-black-700 mb-6">Welcome to IR Real Estates</h1>
            <p class="text-xl text-gray-700 mb-8">Where Property Management Meets Excellence</p>
            <div class="flex justify-center space-x-6">
                <a href="{{ route('register') }}" class="px-7 py-3 bg-indigo-600 text-white rounded-xl text-lg font-semibold hover:bg-indigo-700 transition">Get Started</a>
                <a href="{{ route('login') }}" class="px-7 py-3 bg-white text-indigo-700 border border-indigo-600 rounded-xl text-lg font-semibold hover:bg-gray-100 transition">Login</a>
            </div>
        </div>
    </div>
@endsection

