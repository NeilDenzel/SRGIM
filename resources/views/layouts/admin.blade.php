<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SRGIM - @yield('title', 'Administrador')</title>
    @vite(['resources/css/app.css'])
    @stack('styles')
</head>
<body class="no-nav">

<header class="app-header">
    <div class="header-logo">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
            <circle cx="9" cy="7" r="4"></circle>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
        </svg>
        <span>SRGIM - INVENTARIO</span>
    </div>
    <form action="{{ route('logout') }}" method="POST" style="display:inline; margin-left:auto;">
        @csrf
        <button type="submit" class="header-role-link" style="border:none; background:none; cursor:pointer; padding:4px 8px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <polyline points="16 17 21 12 16 7"></polyline>
                <line x1="21" y1="12" x2="9" y2="12"></line>
            </svg>
            <span class="texto-oculto-movil">Cerrar sesión</span>
        </button>
    </form>
</header>

<main class="contenido">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif
    @yield('content')
</main>

@stack('scripts')
</body>
</html>
