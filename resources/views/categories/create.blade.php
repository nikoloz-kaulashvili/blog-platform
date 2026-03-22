@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto bg-white p-6 rounded shadow">
        <form method="POST" action="{{ route('categories.store') }}">
            @csrf
            <input name="name" required placeholder="Category name" class="w-full border p-2 mb-3 rounded">
            <button class="bg-green-600 text-white px-4 py-2 rounded w-full">
                Save
            </button>
        </form>
    </div>
@endsection
