<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- <title>{{ config('app.name', 'Cuanin') }} - Jual Beli Barang Bekas Berkualitas</title> -->
    <title>Cuanin - Jual Beli Barang Bekas Berkualitas</title>

    <!-- Favicon -->
    <!-- <link rel="icon" href="{{ asset('favicon.ico') }}"> -->
    <link rel="icon" href="{{ asset('icon.png') }}?v=2">

    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    {{-- Stack untuk CSS tambahan dari child view --}}
    @stack('styles')
</head>
<body class="font-sans antialiased text-dark bg-background flex flex-col min-h-screen">

    <x-navbar />

    <main class="flex-grow">
        @yield('content')
    </main>

    <x-footer />

    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();

        // Global SweetAlert2 Confirm Action for Forms
        function confirmAction(event, title, text, confirmText = 'Ya, Lanjutkan', confirmColor = '#3b82f6') {
            event.preventDefault();
            const form = event.target.closest('form');
            
            Swal.fire({
                title: `<span class="text-xl md:text-2xl font-bold text-gray-800">${title}</span>`,
                html: `<span class="text-sm md:text-base text-gray-500">${text}</span>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: confirmColor,
                cancelButtonColor: '#f3f4f6',
                confirmButtonText: confirmText,
                cancelButtonText: '<span class="text-gray-700">Batal</span>',
                reverseButtons: true, // Tombol konfirmasi di kanan (di desktop)
                customClass: {
                    popup: 'rounded-[24px] w-[90%] sm:w-[28rem] shadow-2xl p-4 sm:p-6',
                    actions: 'flex flex-col-reverse sm:flex-row w-full gap-2 sm:gap-3 mt-4 sm:mt-6',
                    confirmButton: 'w-full sm:w-auto rounded-xl px-4 sm:px-6 py-2.5 sm:py-3 text-sm sm:text-base font-bold shadow-lg transition-transform hover:scale-105',
                    cancelButton: 'w-full sm:w-auto rounded-xl px-4 sm:px-6 py-2.5 sm:py-3 text-sm sm:text-base font-bold border border-gray-200 transition-colors hover:bg-gray-200'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>

    {{-- Stack untuk JS tambahan dari child view (DI LUAR tag script di atas) --}}
    @stack('scripts')
</body>
</html>