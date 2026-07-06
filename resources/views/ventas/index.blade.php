@extends('layouts.app')
@section('title', 'Ventas')
@section('content')
<section class="resumen-dia">
    <p class="fecha">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;margin-right:4px;">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
            <line x1="16" y1="2" x2="16" y2="6"></line>
            <line x1="8" y1="2" x2="8" y2="6"></line>
            <line x1="3" y1="10" x2="21" y2="10"></line>
        </svg>
        {{ now()->locale('es')->isoFormat('dddd D [de] MMMM') }}
    </p>
    <p class="monto">S/. {{ number_format($totalHoy, 2) }}</p>
    <p class="conteo">{{ $ventasHoy->count() }} ventas hoy</p>
</section>

<h2 class="subtitulo">Ventas de hoy</h2>

@forelse($ventasHoy as $venta)
<article class="venta-card">
    <div class="venta-icono">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line>
            <polygon points="12 22.08 12 12 3 6.92 3 17.08 12 22.08"></polygon>
            <polygon points="12 12 21 6.92 21 17.08 12 22.08"></polygon>
            <polygon points="12 2 3 6.92 12 12 21 6.92 12 2"></polygon>
            <line x1="12" y1="22.08" x2="12" y2="12"></line>
        </svg>
    </div>
    <span class="venta-nombre">{{ $venta->detalles->first()->producto->nombre ?? 'Venta #'.$venta->id }}</span>
    <div class="venta-precios">
        <span>Cant: {{ number_format($venta->detalles->first()->cantidad ?? 0, 3) }} {{ $venta->detalles->first()->producto->unidad_medida ?? 'UN' }}</span>
        <span>P. Unitario: S/. {{ number_format($venta->detalles->first()->precio_venta_unitario ?? 0, 2) }}</span>
        <span>P. Total: S/. {{ number_format($venta->total_venta, 2) }}</span>
    </div>
    <a href="#" class="btn btn-editar" style="padding:6px 16px;font-size:0.8rem;">ver mas...</a>
</article>
@empty
<div class="tarjeta"><span>No hay ventas registradas hoy</span></div>
@endforelse

<div style="margin-top:24px;">
    <a href="{{ route('ventas.create') }}" class="btn btn-agregar btn-full">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        <span>Nueva Venta</span>
    </a>
</div>
@endsection
