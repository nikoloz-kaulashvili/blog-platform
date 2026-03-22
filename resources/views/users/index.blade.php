@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto">

        <h2 class="text-2xl font-bold mb-6">Users</h2>

        @foreach ($users as $user)
            <div class="bg-white p-4 mb-3 rounded-xl shadow flex justify-between items-center">

                <div>
                    <p class="font-semibold">{{ $user->name }}</p>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                </div>

                <form method="POST" action="{{ route('users.update', $user) }}" class="flex items-center gap-2">
                    @csrf
                    @method('PUT')

                    <select name="role" class="border p-2 rounded-lg">
                        <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                        <option value="moderator" {{ $user->role == 'moderator' ? 'selected' : '' }}>Moderator</option>
                    </select>

                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-lg">
                        Save
                    </button>
                </form>

            </div>
        @endforeach

    </div>
@endsection
