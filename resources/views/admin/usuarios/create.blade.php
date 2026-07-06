@extends('layouts.admin')
@section('title', 'Agregar Usuario')
@section('content')
<main class="zona-central">
    <form class="form-card" method="POST" action="{{ route('admin.usuarios.store') }}">
        @csrf
        <h1>Agregar Nuevo Usuario</h1>

        <div class="input-wrapper">
            <div class="input-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </div>
            <input type="text" name="name" placeholder="Nombre de usuario" value="{{ old('name') }}" required>
            @error('name')<span style="color:#8b3a3a;font-size:13px;">{{ $message }}</span>@enderror
        </div>

        <div class="input-wrapper">
            <div class="input-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
            </div>
            <input type="password" name="password" placeholder="Contraseña" required>
            @error('password')<span style="color:#8b3a3a;font-size:13px;">{{ $message }}</span>@enderror
        </div>

        <div class="input-wrapper">
            <div class="input-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
            </div>
            <input type="password" name="password_confirmation" placeholder="Repetir Contraseña" required>
        </div>

        <div class="input-wrapper">
            <div class="input-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                    <polyline points="22,6 12,13 2,6"></polyline>
                </svg>
            </div>
            <input type="email" name="email" placeholder="Correo Electrónico" value="{{ old('email') }}" required>
            @error('email')<span style="color:#8b3a3a;font-size:13px;">{{ $message }}</span>@enderror
        </div>

        <div class="form-acciones">
            <button type="submit" class="btn btn-guardar btn-full">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                    <polyline points="7 3 7 8 15 8"></polyline>
                </svg>
                <span>Guardar</span>
            </button>
            <a href="{{ route('admin.usuarios.index') }}" class="btn-cancelar">Cancelar</a>
        </div>
    </form>
</main>
@endsection
