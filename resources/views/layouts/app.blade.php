<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PDAM Magetan')</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- Vite untuk compile assets --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
    
    {{-- CSS khusus per halaman (opsional) --}}
    @stack('styles')
</head>
<body class="has-sidebar">
    
    {{-- Sidebar yang ga reload --}}
    @include('component.sidebar')
    
    {{-- Main Content --}}
    <main id="main-content">
        @yield('content')
    </main>

    {{-- JS khusus per halaman (opsional) --}}
    @stack('scripts')
</body>
</html>