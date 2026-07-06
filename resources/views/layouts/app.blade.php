<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SRGIM - @yield('title', 'Inventario')</title>
    @vite(['resources/css/app.css'])
    @stack('styles')
</head>
<body>

<header class="app-header">
    <div class="header-logo">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <path d="M16 10a4 4 0 0 1-8 0"></path>
        </svg>
        <span>SRGIM - INVENTARIO</span>
    </div>
    <div style="display:flex; align-items:center; gap:12px;">
        <span style="font-weight:600;">{{ Auth::user()->name }}</span>
        @if(Auth::user()->isAdmin())
        <a href="{{ route('admin.index') }}" class="header-role-link">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
            </svg>
            <span>Administrador</span>
        </a>
        @endif
        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
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
    </div>
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

@include('partials.bottom-nav')

@stack('scripts')
</body>
</html>
