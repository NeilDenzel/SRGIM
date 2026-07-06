@extends('layouts.admin')
@section('title', 'Panel de Administrador')
@section('content')
<a href="{{ route('admin.usuarios.create') }}" class="opcion">Agregar Nuevo Usuario</a>
<a href="{{ route('admin.usuarios.index') }}" class="opcion">Ver Lista de Usuarios</a>
<a href="{{ route('dashboard') }}" class="btn btn-atras" style="margin-top:20px;">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <line x1="19" y1="12" x2="5" y2="12"></line>
        <polyline points="12 19 5 12 12 5"></polyline>
    </svg>
    <span>Volver al inicio</span>
</a>
@endsection
