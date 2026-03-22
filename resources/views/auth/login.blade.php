@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-100">

        <div class="w-full max-w-md bg-white/80 backdrop-blur shadow-xl rounded-2xl p-8">

            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">
                Welcome Back 👋
            </h2>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-2 rounded-lg mb-4 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm text-gray-600 mb-1">Email</label>
                    <input name="email" type="email" value="{{ old('email') }}" placeholder="you@example.com"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none transition">
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1">Password</label>
                    <input name="password" type="password" placeholder="••••••••"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none transition">
                </div>
                <br>
                <button type="submit"
                    class="w-full mt-8 bg-green-600 hover:bg-green-700 text-white py-2.5 rounded-lg font-medium transition shadow-md">
                    Login
                </button>
            </form>

            <p class="text-center text-sm text-gray-600 mt-6">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-green-600 hover:underline font-medium">
                    Register
                </a>
            </p>

        </div>

    </div>
@endsection
