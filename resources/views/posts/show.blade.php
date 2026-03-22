@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white p-6 rounded-2xl shadow">
        <h1 class="text-2xl font-bold text-gray-800 mb-2">
            {{ $post->title }}
        </h1>

        <p class="text-sm text-gray-500 mb-4">
            Category: {{ $post->category->name }}
        </p>

        @if($post->image)
            <img src="{{ asset('storage/' . $post->image) }}"
                 class="w-full h-80 object-cover rounded-lg mb-4">
        @endif

        <p class="text-gray-700 leading-relaxed">
            {{ $post->description }}
        </p>

        <div class="flex gap-2 mt-6">

            <a href="{{ route('posts.index') }}"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                Back
            </a>
        </div>
    </div>
</div>
@endsection