<nav class="bg-white shadow p-4 flex justify-between">
    <div class="flex gap-4">
        <a href="{{ route('posts.index') }}">Posts</a>

        @auth
            @if (auth()->user()->role === 'admin')
                <a href="{{ route('categories.index') }}">Categories</a>
                <a href="{{ route('users.index') }}">Users</a>
            @endif

            @if (auth()->user()->role === 'moderator')
                <a href="{{ route('categories.index') }}">Categories</a>
            @endif
        @endauth

    </div>

    <div class="flex gap-4 items-center">
        @auth
            <div class="relative">

                <button id="notificationBtn" class="relative p-2 rounded-lg hover:bg-gray-100 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-700" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14V11a6 6 0 10-12 0v3c0 .386-.146.735-.405 1.001L4 17h5m6 0a3 3 0 11-6 0h6z" />
                    </svg>

                    @if ($unreadCount > 0)
                        <span id="notificationCount"
                            class="absolute -top-1 -right-1 bg-red-600 text-white text-[10px] min-w-[18px] h-[18px] flex items-center justify-center rounded-full px-1">
                            {{ $unreadCount }}
                        </span>
                    @endif
                </button>

                <div id="notificationDropdown"
                    class="hidden absolute right-0 mt-3 w-96 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 overflow-hidden">

                    <div class="flex justify-between items-center p-4 border-b bg-gray-50">
                        <span class="font-semibold text-gray-800">Notifications</span>
                    </div>

                    <div class="max-h-96 overflow-y-auto">

                        @forelse($notifications as $notification)
                            <div
                                class="px-4 py-3 hover:bg-gray-50 transition cursor-pointer flex gap-3 {{ $notification->is_read ? '' : 'bg-blue-50' }}">

                                <div class="mt-1">
                                    <div
                                        class="w-2 h-2 rounded-full {{ $notification->is_read ? 'bg-gray-300' : 'bg-blue-500' }}">
                                    </div>
                                </div>

                                <div class="flex-1">
                                    <div class="text-sm text-gray-800">
                                        {{ $notification->type }}
                                    </div>

                                    <div class="text-xs text-gray-400 mt-1">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </div>
                                </div>

                            </div>
                        @empty
                            <div class="p-6 text-center text-gray-400 text-sm">
                                No notifications
                            </div>
                        @endforelse

                    </div>

                </div>
            </div>

            <span class="text-sm text-gray-600">
                {{ auth()->user()->email }}
            </span>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="text-red-600 text-sm">Logout</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="text-sm">Login</a>
            <a href="{{ route('register') }}" class="text-sm">Register</a>
        @endauth
    </div>
</nav>
