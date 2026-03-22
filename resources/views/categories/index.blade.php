@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto">

        <div class="flex justify-between mb-4">
            <h2 class="text-xl font-bold">Categories</h2>
            <a href="{{ route('categories.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">
                Create
            </a>
        </div>

        @foreach ($categories as $category)
            <div class="bg-white p-3 mb-2 rounded shadow flex justify-between">
                <span>{{ $category->name }}</span>
                <div class="flex gap-2">
                    <a href="{{ route('categories.edit', $category) }}" class="bg-yellow-500 text-white px-2 py-1 rounded">
                        Edit
                    </a>

                    <form method="POST" action="{{ route('categories.destroy', $category) }}">
                        @csrf
                        @method('DELETE')
                        <button class="bg-red-600 text-white px-2 py-1 rounded">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@endsection
