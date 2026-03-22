@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto">

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Posts</h2>

            <a href="{{ route('posts.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow">
                + Create Post
            </a>
        </div>

        <form method="GET" action="{{ route('posts.index') }}" class="bg-white p-4 rounded-2xl shadow mb-6">

            <div class="flex flex-wrap items-center gap-3">

                {{-- TITLE --}}
                <input type="text" name="title" value="{{ request('title') }}" placeholder="Search by title..."
                    class="border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-2 rounded-lg w-64">

                {{-- CATEGORY --}}
                <select name="category_id"
                    class="border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-2 rounded-lg w-52">
                    <option value="">All Categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                {{-- FILTER --}}
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow-sm transition">
                    Filter
                </button>

                {{-- RESET --}}
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow-sm transition">
                    <a href="{{ route('posts.index') }}">
                        Reset
                    </a>
                </button>


            </div>

        </form>

        @foreach ($posts as $post)
            <div class="bg-white p-5 mb-4 rounded-2xl shadow hover:shadow-md transition">

                <div class="flex justify-between items-start">

                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">
                            {{ $post->title }}
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            {{ $post->category->name }}
                        </p>
                    </div>

                    {{-- STATUS --}}
                    <div>
                        @if ($post->status === 'approved')
                            <span class="bg-blue-100 text-green-700 text-xs px-3 py-1 rounded-full">
                                Approved
                            </span>
                        @elseif ($post->status === 'pending')
                            <span class="bg-blue-100 text-yellow-700 text-xs px-3 py-1 rounded-full">
                                Pending
                            </span>
                        @else
                            <span class="bg-red-100 text-red-700 text-xs px-3 py-1 rounded-full">
                                Rejected
                            </span>
                        @endif
                    </div>

                </div>

                {{-- ACTIONS --}}
                <div class="flex flex-wrap gap-2 mt-4">

                    {{-- VIEW --}}
                    <a href="{{ route('posts.show', $post) }}"
                        class="bg-green-600 hover:bg-gray-800 text-white px-3 py-1 rounded-lg text-sm">
                        View
                    </a>

                    {{-- EDIT --}}
                    @if (auth()->id() === $post->user_id || in_array(auth()->user()->role, ['admin', 'moderator']))
                        <a href="{{ route('posts.edit', $post) }}"
                            class="bg-green-600 hover:bg-green-600 text-white px-3 py-1 rounded-lg text-sm">
                            Edit
                        </a>
                    @endif

                    {{-- DELETE --}}
                    @if (auth()->id() === $post->user_id || auth()->user()->role === 'admin')
                        <form method="POST" action="{{ route('posts.destroy', $post) }}">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Are you sure?')"
                                class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-lg text-sm">
                                Delete
                            </button>
                        </form>
                    @endif

                    {{-- APPROVE / REJECT (Moderator + Admin) --}}
                    @if (in_array(auth()->user()->role, ['admin', 'moderator']) && $post->status === 'pending')
                        <form method="POST" action="{{ route('posts.approve', $post) }}">
                            @csrf
                            <button class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-lg text-sm">
                                Approve
                            </button>
                        </form>

                        <form method="POST" action="{{ route('posts.reject', $post) }}">
                            @csrf
                            <button class="bg-green-600 hover:bg-green-600 text-white px-3 py-1 rounded-lg text-sm">
                                Reject
                            </button>
                        </form>
                    @endif

                </div>

            </div>
        @endforeach

    </div>
@endsection
