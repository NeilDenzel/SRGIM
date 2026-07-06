@extends('layouts.app')
@section('title', 'Editar Producto')
@section('content')
<main class="zona-central" style="padding-top:0;">
    <form class="form-card" method="POST" action="{{ route('catalogos.update', $producto) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <h1>Editar Producto</h1>

        <div class="input-wrapper">
            <div class="input-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                </svg>
            </div>
            <input type="text" name="nombre" placeholder="Nombre del producto" value="{{ old('nombre', $producto->nombre) }}" required>
            @error('nombre')<span style="color:#8b3a3a;font-size:13px;">{{ $message }}</span>@enderror
        </div>

        <div class="input-wrapper">
            <div class="input-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                </svg>
            </div>
            <select name="categoria_id" required>
                <option value="">Seleccionar categoría</option>
                @foreach($categorias as $cat)
                <option value="{{ $cat->id }}" {{ old('categoria_id', $producto->categoria_id) == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                @endforeach
            </select>
            @error('categoria_id')<span style="color:#8b3a3a;font-size:13px;">{{ $message }}</span>@enderror
        </div>

        <div class="input-wrapper">
            <div class="input-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                    <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
            </div>
            <input type="file" name="foto" accept="image/*">
            <span style="font-size:0.8rem;color:var(--texto-gris);">Dejar vacío para mantener la foto actual</span>
            @error('foto')<span style="color:#8b3a3a;font-size:13px;">{{ $message }}</span>@enderror
        </div>

        <div class="input-wrapper">
            <div class="input-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2v20M2 12h20M2 2l20 20M22 2L2 20"></path>
                </svg>
            </div>
            <select name="unidad_medida" required>
                <option value="UN" {{ old('unidad_medida', $producto->unidad_medida) == 'UN' ? 'selected' : '' }}>UN (Unidad)</option>
                <option value="KG" {{ old('unidad_medida', $producto->unidad_medida) == 'KG' ? 'selected' : '' }}>KG (Kilogramo)</option>
                <option value="LT" {{ old('unidad_medida', $producto->unidad_medida) == 'LT' ? 'selected' : '' }}>LT (Litro)</option>
                <option value="MTS" {{ old('unidad_medida', $producto->unidad_medida) == 'MTS' ? 'selected' : '' }}>MTS (Metro)</option>
            </select>
            @error('unidad_medida')<span style="color:#8b3a3a;font-size:13px;">{{ $message }}</span>@enderror
        </div>

        <div style="border:3px solid var(--borde);border-radius:var(--border-radius-sm);padding:16px;margin-bottom:20px;background:#f7f9fa;">
            <div style="display:flex;justify-content:space-between;align-items:center;">
                <div>
                    <span style="font-weight:700;font-size:1.1rem;">Stock actual: {{ $producto->stock_formateado }} {{ $producto->unidad_medida }}</span>
                </div>
                <a href="{{ route('ingresos.create', ['producto_id' => $producto->id]) }}" class="btn btn-agregar" style="padding:8px 20px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Agregar Stock</span>
                </a>
            </div>
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
            <a href="{{ route('catalogos.index') }}" class="btn-cancelar">Cancelar</a>
        </div>
    </form>
</main>
@endsection
