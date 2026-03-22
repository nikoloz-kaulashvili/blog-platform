@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded-lg shadow w-full max-w-md">

    <h2 class="text-xl font-bold mb-4 text-center">Register</h2>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-2 rounded mb-3">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <input name="name"
               placeholder="Name"
               value="{{ old('name') }}"
               class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">

        <input name="email"
               type="email"
               placeholder="Email"
               value="{{ old('email') }}"
               class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">

        <input name="password"
               type="password"
               placeholder="Password"
               class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">

        <input name="password_confirmation"
               type="password"
               placeholder="Confirm Password"
               class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">

        <button class="w-full bg-blue-600 hover:bg-blue-700 text-white p-2 rounded">
            Register
        </button>
    </form>

    <p class="text-center text-sm mt-4">
        Already have an account?
        <a href="{{ route('login') }}" class="text-blue-500 hover:underline">
            Login
        </a>
    </p>

</div>
@endsection