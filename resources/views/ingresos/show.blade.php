@extends('layouts.app')
@section('title', 'Ingreso #' . $ingreso->id)
@section('content')
<div class="barra-titulo">
    <h1>Ingreso #{{ $ingreso->id }}</h1>
    <a href="{{ route('ingresos.index') }}" class="btn btn-atras">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
        <span>Atras</span>
    </a>
</div>

<div class="resumen-dia" style="margin-bottom:20px;">
    <p class="fecha">Registrado el {{ $ingreso->created_at->format('d/m/Y H:i') }}</p>
    <p class="monto">S/. {{ number_format($ingreso->total_ingreso, 2) }}</p>
    <p class="conteo">Por: {{ $ingreso->user->name }}</p>
</div>

<h2 class="subtitulo">Productos</h2>

@foreach($ingreso->detalles as $detalle)
<div class="venta-card">
    <div class="venta-icono">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
        </svg>
    </div>
    <span class="venta-nombre">{{ $detalle->producto->nombre }}</span>
    <div class="venta-precios">
        <span>{{ $detalle->cantidad_paquetes }} paq. × {{ $detalle->unidades_por_paquete }} {{ $detalle->producto->unidad_medida }}/paq</span>
        <span>Cant: {{ number_format($detalle->cantidad_inicial, 3) }} {{ $detalle->producto->unidad_medida }}</span>
        <span>Stock: {{ number_format($detalle->stock_restante, 3) }} {{ $detalle->producto->unidad_medida }}</span>
        <span>P. paquete: S/. {{ number_format($detalle->precio_paquete, 2) }}</span>
        <span>P. unitario: S/. {{ number_format($detalle->precio_costo_unitario, 2) }}</span>
        <span>Vence: {{ $detalle->fecha_vencimiento->format('d/m/Y') }}</span>
    </div>
</div>
@endforeach

<a href="{{ route('dashboard') }}" class="btn btn-atras" style="margin-top:20px;">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <line x1="19" y1="12" x2="5" y2="12"></line>
        <polyline points="12 19 5 12 12 5"></polyline>
    </svg>
    <span>Volver al inicio</span>
</a>
@endsection
