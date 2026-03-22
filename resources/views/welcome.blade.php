@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto">

        <form method="GET" action="{{ route('main.index') }}" class="bg-white p-4 rounded-2xl shadow mb-6">
            <div class="flex flex-wrap items-center gap-3">
                <input type="text" name="title" value="{{ request('title') }}" placeholder="Search by title..."
                    class="border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-2 rounded-lg w-64">

                <select name="category_id"
                    class="border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-2 rounded-lg w-52">
                    <option value="">All Categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow-sm transition">
                    Filter
                </button>

                <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow-sm transition">
                    <a href="{{ route('main.index') }}">
                        Reset
                    </a>
                </button>
            </div>
        </form>

        {{-- POSTS --}}
        <div class="mt-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            @forelse($posts as $post)
                <div class="bg-white p-4 rounded-xl shadow hover:shadow-lg transition">

                    <h2 class="text-lg font-bold text-gray-800">
                        {{ $post->title }}
                    </h2>

                    <p class="text-xs text-gray-400">
                        {{ $post->created_at->format('d.m.Y H:i') }}
                    </p>

                    <p class="text-sm text-gray-500 mt-1">
                        ავტორი: {{ $post->user->name }}
                    </p>

                    <div class="flex justify-between items-center mt-4">

                        <span class="text-xs text-gray-500">
                            {{ $post->comments_count }} კომენტარი
                        </span>

                        <a href="{{ route('posts.show', $post) }}" class="text-blue-600 text-sm hover:underline">
                            მეტის ნახვა →
                        </a>

                    </div>

                </div>
            @empty
                <p class="text-gray-500">ამჟამად პოსტი არარის</p>
            @endforelse

        </div>

    </div>
@endsection
