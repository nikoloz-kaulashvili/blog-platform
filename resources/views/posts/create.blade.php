@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4">Create Post</h2>
        <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
            @csrf
            <input name="title" placeholder="Title" class="w-full border p-2 mb-3 rounded">
            <textarea name="description" placeholder="Description" class="w-full border p-2 mb-3 rounded"></textarea>
            <select name="category_id" class="w-full border p-2 mb-3 rounded">
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            <input type="file" name="image" class="mb-3">
            <button class="bg-green-600 text-white px-4 py-2 rounded w-full">
                Save
            </button>
        </form>
    </div>
@endsection
