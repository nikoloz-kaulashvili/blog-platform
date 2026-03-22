<div id="toastContainer" class="fixed bottom-5 right-5 z-50 space-y-3"></div>

@if (session('toast'))
    <script>
        window.toastData = {
            message: "{{ session('toast.message') }}",
            type: "{{ session('toast.type') }}"
        };
    </script>
@endif