<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Blog Platform')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">
    @include('partials.navbar')

    <main class="flex-1 p-6">
        @yield('content')
    </main>

    @include('partials.toast')

    @if (session('success') || session('error'))
        <div id="toast"
            class="fixed mt-8 top-16 right-5 px-4 py-3 rounded-lg shadow-lg text-white z-50
         {{ session('success') ? 'bg-green-600' : 'bg-red-600' }}">

            {{ session('success') ?? session('error') }}
        </div>

        <script>
            setTimeout(() => {
                const toast = document.getElementById('toast');
                if (toast) {
                    toast.style.opacity = '0';
                    toast.style.transition = 'opacity 0.5s ease';
                    setTimeout(() => toast.remove(), 500);
                }
            }, 3000);
        </script>
    @endif
    
    <script>
        const btn = document.getElementById('notificationBtn');
        const dropdown = document.getElementById('notificationDropdown');

        if (btn) {
            btn.addEventListener('click', () => {
                dropdown.classList.toggle('hidden');
                fetch('/notifications/read', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                }).then(() => {
                    const counter = document.getElementById('notificationCount');
                    if (counter) {
                        counter.remove();
                    }
                    document.querySelectorAll('#notificationDropdown .bg-blue-50')
                        .forEach(el => el.classList.remove('bg-blue-50'));

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

            toast.className = `
                    ${styles[type] || 'bg-gray-800'}
                    text-white px-5 py-4 rounded-2xl shadow-xl
                    flex items-center gap-3
                    min-w-[260px] max-w-[320px]
                    animate-slide-in
                `;

            toast.innerHTML = `
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
                message = '✅ თქვენი პოსტი დადასტურებულია';
                type = 'success';
            } else if (status === 'rejected') {
                message = 'თქვენი პოსტი უარყოფილია';
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

        channel.bind('post.created', function(data) {
            console.log(data);

            showToast('დაემატა ახალი პოსტი', 'info');

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
                            New post: ${data.post.title}
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
