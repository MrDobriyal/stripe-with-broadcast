<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My App')</title>
     <meta name="csrf-token" content="{{ csrf_token() }}">
       @vite(['resources/js/app.js'])
    <!-- Global Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Page Specific Styles -->
    @yield('styles')
</head>
<body>

    {{-- Header --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">My App</a>
        </div>
    </nav>

    {{-- Main Content --}}
    <main class="container mt-4">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="text-center mt-5 mb-3 text-muted">
        © {{ date('Y') }} My App
    </footer>

    <!-- Global Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Page Specific Scripts -->
    @yield('scripts')
</body>
</html>
