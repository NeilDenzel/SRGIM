@extends('layouts.app')
@section('title', 'Catálogo')
@section('content')
<section class="panel">
    <div class="panel-item">
        <span class="icono">📦</span>
        <span>Productos por vencer: <b>{{ $porVencer }}</b></span>
    </div>
    <a href="{{ route('catalogos.create') }}" class="panel-item add" style="text-decoration:none;color:inherit;">
        <span class="icono">➕</span>
        <span>Agregar nuevo producto</span>
    </a>
</section>

<div class="tarjeta">
    <form method="GET" action="{{ route('dashboard') }}" style="width:100%;display:flex;">
        <input type="text" name="buscar" placeholder="Buscar..." value="{{ request('buscar') }}" style="padding:10px;border:3px solid var(--borde);border-radius:var(--border-radius-sm);font-size:18px;width:100%;">
    </form>
</div>

@forelse($productos as $producto)
<div class="venta-card">
    <div class="venta-icono">
        @if($producto->foto_ruta)
        <img src="{{ Storage::url($producto->foto_ruta) }}" alt="{{ $producto->nombre }}" style="width:100%;height:100%;object-fit:cover;">
        @else
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
        </svg>
        @endif
    </div>
    <span class="venta-nombre">{{ $producto->nombre }}</span>
    <div class="venta-precios">
        <span>Stock: {{ $producto->stock_formateado }} {{ $producto->unidad_medida }}</span>
    </div>
    <div style="display:flex; gap:6px;">
        <a href="{{ route('ingresos.create', ['producto_id' => $producto->id]) }}" class="btn btn-agregar" style="padding:6px 16px;font-size:0.8rem;">
            <span>+ Stock</span>
        </a>
        <a href="{{ route('catalogos.edit', $producto) }}" class="btn btn-editar" style="padding:6px 16px;font-size:0.8rem;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
            </svg>
            <span>Editar</span>
        </a>
    </div>
</div>
@empty
<div class="tarjeta"><span>No hay productos registrados</span></div>
@endforelse

<div style="margin-top:20px;">
    <a href="{{ route('ingresos.create') }}" class="btn btn-agregar">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        <span>Agregar Stock / Registrar Compra</span>
    </a>
</div>
@endsection
