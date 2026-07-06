@extends('layouts.app')
@section('title', 'Catálogo de Productos')
@section('content')
<div class="barra-titulo">
    <h1>Catálogo de Productos</h1>
    <a href="{{ route('catalogos.create') }}" class="btn btn-agregar">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        <span>Nuevo</span>
    </a>
</div>

<div class="tarjeta">
    <form method="GET" action="{{ route('catalogos.index') }}" style="width:100%;display:flex;">
        <input type="text" name="buscar" placeholder="Buscar producto..." value="{{ request('buscar') }}" style="padding:10px;border:3px solid var(--borde);border-radius:var(--border-radius-sm);font-size:18px;width:100%;">
    </form>
</div>

@forelse($productos as $producto)
<div class="venta-card">
    <div class="venta-icono" onclick="window.location='{{ route('catalogos.edit', $producto) }}'" style="cursor:pointer;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
        </svg>
    </div>
    <span class="venta-nombre" onclick="window.location='{{ route('catalogos.edit', $producto) }}'" style="cursor:pointer;">{{ $producto->nombre }}</span>
    <div class="venta-precios">
        <span>Stock: {{ $producto->stock_formateado }} {{ $producto->unidad_medida }}</span>
        <span>{{ $producto->categoria->nombre ?? 'Sin categoría' }}</span>
    </div>
    <a href="{{ route('ingresos.create', ['producto_id' => $producto->id]) }}" class="btn btn-agregar" style="padding:6px 16px;font-size:0.8rem;">
        <span>+ Stock</span>
    </a>
</div>
@empty
<div class="tarjeta"><span>No hay productos registrados</span></div>
@endforelse
@endsection
