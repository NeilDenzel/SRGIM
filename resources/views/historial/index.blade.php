@extends('layouts.app')
@section('title', 'Historial')
@section('content')
<div class="section-title">Movimientos de inventario</div>

@forelse($movimientos as $mov)
<div class="card">
    <div class="card-left">
        <div class="icon-box">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="#4a4a4a" stroke-width="1.5"><path d="M21 8L12 3 3 8v8l9 5 9-5V8z"/><path d="M3 8l9 5 9-5"/><path d="M12 13v8"/></svg>
        </div>
        <span class="product-name">{{ $mov->producto->nombre }}</span>
        <span style="font-size:12px;color:var(--texto-gris);margin-left:8px;">{{ $mov->created_at->format('d/m/Y H:i') }}</span>
    </div>
    <span class="badge {{ $mov->tipo === 'ingreso' ? 'badge-ingreso' : ($mov->tipo === 'venta' ? 'badge-venta' : 'badge-caducado') }}">
        {{ ucfirst($mov->tipo) }}
    </span>
</div>
@empty
<div class="card"><div class="card-left"><span class="product-name">No hay movimientos registrados</span></div></div>
@endforelse
@endsection
