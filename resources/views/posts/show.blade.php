@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">

    <div class="bg-white p-6 rounded-2xl shadow">

        {{-- TITLE --}}
        <h1 class="text-2xl font-bold text-gray-800 mb-2">
            {{ $post->title }}
        </h1>

        {{-- CATEGORY --}}
        <p class="text-sm text-gray-500 mb-4">
            Category: {{ $post->category->name }}
        </p>

        {{-- STATUS --}}
        <div class="mb-4">
            @if ($post->status === 'approved')
                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">Approved</span>
            @elseif ($post->status === 'pending')
                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">Pending</span>
            @else
                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">Rejected</span>
            @endif
        </div>

        {{-- IMAGE --}}
        @if($post->image)
            <img src="{{ asset('storage/' . $post->image) }}"
                 class="w-full h-80 object-cover rounded-lg mb-4">
        @endif

        {{-- DESCRIPTION --}}
        <p class="text-gray-700 leading-relaxed">
            {{ $post->description }}
        </p>

        {{-- ACTIONS --}}
        <div class="flex gap-2 mt-6">

            <a href="{{ route('posts.index') }}"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                Back
            </a>

            @if(auth()->id() === $post->user_id || in_array(auth()->user()->role, ['admin','moderator']))
                <a href="{{ route('posts.edit', $post) }}"
                   class="bg-green-600 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                    Edit
                </a>
            @endif

            @if(auth()->id() === $post->user_id || auth()->user()->role === 'admin')
                <form method="POST" action="{{ route('posts.destroy', $post) }}">
                    @csrf
                    @method('DELETE')
                    <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                        Delete
                    </button>
                </form>
            @endif

        </div>

    </div>

</div>
@endsection