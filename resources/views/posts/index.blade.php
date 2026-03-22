@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto">

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">პოსტები</h2>

            <a href="{{ route('posts.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow">
                + პოსტის შექმნა
            </a>
        </div>

        <form method="GET" action="{{ route('posts.index') }}" class="bg-white p-4 rounded-2xl shadow mb-6">
            <div class="flex flex-wrap items-center gap-3">
                <input type="text" name="title" value="{{ request('title') }}" placeholder="Search by title..."
                    class="border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-2 rounded-lg w-64">

                <select name="category_id"
                    class="border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-2 rounded-lg w-52">
                    <option value="">კატეგორიები</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow-sm transition">
                    ფილტრები
                </button>

                <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow-sm transition">
                    <a href="{{ route('posts.index') }}">
                        გასუფთავება
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
                    <div>
                        @if ($post->status === 'approved')
                            <span class="bg-blue-100 text-green-700 text-xs px-3 py-1 rounded-full">
                                დადასტურებული
                            </span>
                        @elseif ($post->status === 'pending')
                            <span class="bg-blue-100 text-yellow-700 text-xs px-3 py-1 rounded-full">
                                მოლოდინის რეჟიმში
                            </span>
                        @elseif ($post->status === 'edited')
                            <span class="bg-blue-100 text-yellow-700 text-xs px-3 py-1 rounded-full">
                                შესწორებული
                            </span>
                        @else
                            <span class="bg-red-100 text-red-700 text-xs px-3 py-1 rounded-full">
                                უარყოფილი
                            </span>
                        @endif
                    </div>
                </div>

                <div class="flex flex-wrap gap-2 mt-4">
                    <a href="{{ route('posts.show', $post) }}"
                        class="bg-green-600 hover:bg-gray-800 text-white px-3 py-1 rounded-lg text-sm">
                        ნახვა
                    </a>

                    @if (auth()->id() === $post->user_id &&
                            ($post->status === 'pending' || $post->status === 'edited' || $post->status === 'rejected'))
                        <a href="{{ route('posts.edit', $post) }}"
                            class="bg-green-600 hover:bg-green-600 text-white px-3 py-1 rounded-lg text-sm">
                            შეცვლა
                        </a>
                    @endif

                    @if (auth()->id() === $post->user_id || auth()->user()->role === 'admin')
                        <form method="POST" action="{{ route('posts.destroy', $post) }}">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Are you sure?')"
                                class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-lg text-sm">
                                წაშლა
                            </button>
                        </form>
                    @endif

                    @if (
                        (auth()->user()->role === 'admin' || auth()->user()->role === 'moderator') &&
                            ($post->status === 'pending' || $post->status === 'edited'))
                        <form method="POST" action="{{ route('posts.approve', $post) }}">
                            @csrf
                            <button class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-lg text-sm">
                                დადასტურება
                            </button>
                        </form>

                        <form method="POST" action="{{ route('posts.reject', $post) }}">
                            @csrf
                            <button class="bg-green-600 hover:bg-green-600 text-white px-3 py-1 rounded-lg text-sm">
                                უარყოფა
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endsection
