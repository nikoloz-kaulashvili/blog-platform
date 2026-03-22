@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4">Edit Post</h2>
        <form method="POST" action="{{ route('posts.update', $post) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input name="title" value="{{ $post->title }}" class="w-full border p-2 mb-3 rounded">
            <textarea name="description" class="w-full border p-2 mb-3 rounded">{{ $post->description }}</textarea>
            <select name="category_id" class="w-full border p-2 mb-3 rounded">
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ $post->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <input type="file" name="image" class="mb-3">

            <button class="bg-blue-600 text-white px-4 py-2 rounded w-full">
                Update
            </button>
        </form>
    </div>
@endsection
