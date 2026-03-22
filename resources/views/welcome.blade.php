@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-center min-h-[70vh]">

        <div class="text-center max-w-xl">

            <h1 class="text-4xl font-bold text-gray-800 mb-4">
                Welcome to Our Blog Platform
            </h1>

            <p class="text-gray-500 text-lg mb-6">
                Discover ideas, share your thoughts, and explore content created by our community.
            </p>

            @auth
                @if (auth()->user()->role === 'user')
                    <a href="{{ route('posts.create') }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded transition">
                        Create Your First Post
                    </a>
                @endif
            @else
                <div class="flex justify-center gap-3">
                    <a href="{{ route('login') }}" class="bg-gray-800 text-white px-5 py-2 rounded">
                        Login
                    </a>

                    <a href="{{ route('register') }}" class="bg-blue-600 text-white px-5 py-2 rounded">
                        Register
                    </a>
                </div>
            @endauth

        </div>

    </div>
@endsection
