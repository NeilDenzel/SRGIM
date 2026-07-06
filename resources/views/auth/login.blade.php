@extends('layouts.auth')
@section('title', 'Iniciar Sesión')
@section('content')
<section class="login-card">
    <h2>Iniciar sesión</h2>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="campo">
            <input type="text" name="email" placeholder="Usuario o Correo" value="{{ old('email') }}" required autofocus>
            @error('email')<span style="color:#8b3a3a;font-size:13px;">{{ $message }}</span>@enderror
        </div>
        <div class="campo">
            <input type="password" name="password" placeholder="Contraseña" required>
            @error('password')<span style="color:#8b3a3a;font-size:13px;">{{ $message }}</span>@enderror
        </div>
        <button type="submit" class="btn btn-guardar btn-full">Ingresar</button>
    </form>
</section>
@endsection
