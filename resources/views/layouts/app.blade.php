<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Blog Platform</title>
    @vite('resources/css/app.css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-gray-100">

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

                {{-- 🔔 NOTIFICATIONS --}}
                <div class="relative">

                    <button id="notificationBtn" class="relative p-2 rounded-lg hover:bg-gray-100 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-700" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
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

    <div class="p-6">
        @yield('content')
    </div>
    <div id="toastContainer" class="fixed bottom-5 right-5 z-50 space-y-3"></div>
    {{-- JS --}}
    <script>
        const btn = document.getElementById('notificationBtn');
        const dropdown = document.getElementById('notificationDropdown');

        if (btn) {
            btn.addEventListener('click', () => {

                // dropdown toggle
                dropdown.classList.toggle('hidden');

                // mark as read
                fetch('/notifications/read', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                }).then(() => {

                    // 🔥 counter reset
                    const counter = document.getElementById('notificationCount');
                    if (counter) {
                        counter.remove(); // ან შეგიძლია hidden გააკეთო
                    }

                    // 🔥 ყველა ნოთიფიკაციას მოვხსნათ ლურჯი ფონი
                    document.querySelectorAll('#notificationDropdown .bg-blue-50')
                        .forEach(el => el.classList.remove('bg-blue-50'));

                    // 🔥 წერტილებიც გავაგრეიოთ
                    document.querySelectorAll('#notificationDropdown .bg-blue-500')
                        .forEach(el => el.classList.replace('bg-blue-500', 'bg-gray-300'));
                });
            });

            document.addEventListener('click', (e) => {
                if (!btn.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.classList.add('hidden');
                }
            });
        }
    </script>

    <script>
        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');

            const toast = document.createElement('div');

            const styles = {
                success: 'bg-green-600',
                error: 'bg-red-600',
                info: 'bg-blue-600'
            };

            const icons = {
                success: '✔',
                error: '✖',
                info: 'ℹ'
            };

            toast.className = `
        ${styles[type] || 'bg-gray-800'}
        text-white px-5 py-4 rounded-2xl shadow-xl
        flex items-center gap-3
        min-w-[260px] max-w-[320px]
        animate-slide-in
    `;

            toast.innerHTML = `
        <span class="text-lg">${icons[type]}</span>
        <div class="flex-1 text-sm">${message}</div>
        <button class="text-white/70 hover:text-white text-lg">&times;</button>
    `;

            toast.querySelector('button').onclick = () => toast.remove();

            container.appendChild(toast);

            setTimeout(() => {
                toast.remove();
            }, 4000);
        }
    </script>

    @if (session('toast'))
        <script>
            showToast("{{ session('toast.message') }}", "{{ session('toast.type') }}");
        </script>
    @endif

    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>

    <script>
        const pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
            cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
            authEndpoint: '/broadcasting/auth',
            auth: {
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            }
        });

        const channel = pusher.subscribe('private-user.{{ auth()->id() }}');

        channel.bind('post.status.updated', function(data) {
            console.log(data);

            const status = data.post.status;

            let message = '';
            let type = 'info';

            if (status === 'approved') {
                message = '✅ Your post was approved';
                type = 'success';
            } else if (status === 'rejected') {
                message = '❌ Your post was rejected';
                type = 'error';
            }

            showToast(message, type);

            const counter = document.getElementById('notificationCount');
            const btn = document.getElementById('notificationBtn');

            if (counter) {
                let current = parseInt(counter.innerText) || 0;
                counter.innerText = current + 1;
            } else {
                const span = document.createElement('span');
                span.id = 'notificationCount';
                span.className = `
                    absolute -top-1 -right-1 bg-red-600 text-white text-[10px]
                    min-w-[18px] h-[18px] flex items-center justify-center rounded-full px-1
                `;
                span.innerText = 1;

                btn.appendChild(span);
            }

            // 🔥 DROPDOWN UPDATE
            const container = document.querySelector('#notificationDropdown .max-h-96');

            if (container) {
                const item = document.createElement('div');

                item.className = `
            px-4 py-3 bg-blue-50 flex gap-3 border-b
            `;

                item.innerHTML = `
                <div class="mt-1">
                    <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                </div>
                <div class="flex-1">
                    <div class="text-sm text-gray-800">
                        ${data.post.title} - ${data.post.status}
                    </div>
                    <div class="text-xs text-gray-400 mt-1">
                        just now
                    </div>
                </div>
            `;

                container.prepend(item);
            }
        });
    </script>

</body>

</html>
