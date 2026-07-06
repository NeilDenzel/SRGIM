@extends('layouts.app')
@section('title', 'Ingresos')
@section('content')
<div class="barra-titulo">
    <h1>Historial de Ingresos</h1>
    <a href="{{ route('ingresos.create') }}" class="btn btn-agregar">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        <span>Nuevo Ingreso</span>
    </a>
</div>

@forelse($ingresos as $ingreso)
<div class="venta-card">
    <div class="venta-icono">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line>
            <polygon points="12 22.08 12 12 3 6.92 3 17.08 12 22.08"></polygon>
            <polygon points="12 12 21 6.92 21 17.08 12 22.08"></polygon>
            <polygon points="12 2 3 6.92 12 12 21 6.92 12 2"></polygon>
            <line x1="12" y1="22.08" x2="12" y2="12"></line>
        </svg>
    </div>
    <span class="venta-nombre">Ingreso #{{ $ingreso->id }}</span>
    <div class="venta-precios">
        <span>{{ $ingreso->created_at->format('d/m/Y H:i') }}</span>
        <span>S/. {{ number_format($ingreso->total_ingreso, 2) }}</span>
        <span>{{ $ingreso->user->name }}</span>
    </div>
    <a href="{{ route('ingresos.show', $ingreso) }}" class="btn btn-editar" style="padding:6px 16px;font-size:0.8rem;">Ver</a>
</div>
@empty
<div class="tarjeta"><span>No hay ingresos registrados</span></div>
@endforelse
@endsection
