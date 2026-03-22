@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white p-6 rounded-2xl shadow">

            <h1 class="text-2xl font-bold text-gray-800 mb-3">
                {{ $post->title }}
            </h1>

            <div class="text-sm text-gray-500 mb-4 space-y-1">
                <p>ავტორი: {{ $post->user->name }}</p>
                <p>კატეგორია: {{ $post->category->name }}</p>
                <p>თარიღი: {{ $post->created_at->format('d.m.Y H:i') }}</p>
            </div>

            @if ($post->image)
                <div class="mb-4 overflow-hidden rounded-lg">
                    <img src="{{ asset('storage/' . $post->image) }}"
                        class="w-full h-80 object-cover hover:scale-105 transition duration-300">
                </div>
            @endif

            <p class="text-gray-700 leading-relaxed">
                {{ $post->description }}
            </p>

        </div>



        <div class="mt-8 bg-white p-6 rounded-2xl shadow">

            <h2 class="text-lg font-semibold mb-4">კომენტარები</h2>

            @auth
                <form method="POST" action="{{ route('comments.store', $post) }}" class="mb-6">
                    @csrf

                    <textarea name="content" class="w-full border p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Write a comment..." required></textarea>

                    <button class="mt-3 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        კომენტარის დამატება
                    </button>
                </form>
            @endauth

            @guest
                <p class="text-gray-500">კომენტარის დასამატებლად გაიაღეთ ავტორიზაცია</p>
            @endguest

        </div>

        <div class="mt-4 space-y-4">

            @forelse($post->comments as $comment)
                <div class="bg-white p-4 rounded-lg">

                    <p class="font-semibold text-gray-800">
                        {{ $comment->user->name }}
                    </p>

                    <p class="text-gray-700 mt-1">
                        {{ $comment->content }}
                    </p>

                    @auth
                        <form method="POST" action="{{ route('comments.store', $post) }}"
                            class="mt-3 flex gap-2 items-center">
                            @csrf
                            <input type="hidden" name="parent_id" value="{{ $comment->id }}">

                            <input name="content"
                                class="flex-1 border p-2 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Reply..." required>

                            <button class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm transition">
                                პასუხი
                            </button>
                        </form>
                    @endauth

                    @foreach ($comment->replies as $reply)
                        <div class="ml-6 mt-3 border-l pl-4">

                            <p class="font-semibold text-gray-800">
                                {{ $reply->user->name }}
                            </p>

                            <p class="text-gray-700 text-sm">
                                {{ $reply->content }}
                            </p>

                        </div>
                    @endforeach

                </div>
            @empty
                <p class="text-gray-500">ამჟამად ამ პოსტზე კომენტარი არ არის</p>
            @endforelse

        </div>
    </div>
@endsection
