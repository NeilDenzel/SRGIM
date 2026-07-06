<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SRGIM - @yield('title', 'Inventario')</title>
    @vite(['resources/css/app.css'])
</head>
<body class="no-nav" style="display:flex; flex-direction:column; min-height:100vh;">

<header class="app-header">
    <div class="header-logo">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <path d="M16 10a4 4 0 0 1-8 0"></path>
        </svg>
        <span>SRGIM - INVENTARIO</span>
    </div>
</header>

<main class="login-wrapper">
    @yield('content')
</main>

</body>
</html>
