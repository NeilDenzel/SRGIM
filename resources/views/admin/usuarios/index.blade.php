@extends('layouts.admin')
@section('title', 'Lista de Usuarios')
@section('content')
<div class="barra-titulo">
    <h1>Lista de Usuarios</h1>
    <a href="{{ route('dashboard') }}" class="btn btn-atras">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
        <span>Atras</span>
    </a>
</div>

<div class="usuarios-list">
    @forelse($usuarios as $usuario)
    <div class="usuario-card">
        <div class="usuario-info">
            <div class="usuario-avatar">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </div>
            <span class="usuario-nombre">{{ $usuario->name }}</span>
            <span style="font-size:0.85rem;color:var(--texto-gris);">{{ $usuario->email }}</span>
        </div>
        <div class="usuario-acciones">
            <a href="{{ route('admin.usuarios.edit', $usuario) }}" class="btn btn-editar">Editar</a>
            <form method="POST" action="{{ route('admin.usuarios.destroy', $usuario) }}" onsubmit="return confirm('¿Eliminar usuario?')" style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-eliminar">Eliminar</button>
            </form>
        </div>
    </div>
    @empty
    <div class="tarjeta"><span>No hay usuarios registrados</span></div>
    @endforelse
</div>

<div class="contenedor-agregar">
    <a href="{{ route('admin.usuarios.create') }}" class="btn btn-agregar">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        <span>Agregar Nuevo Usuario</span>
    </a>
</div>
@endsection
