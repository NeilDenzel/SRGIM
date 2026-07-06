@extends('layouts.app')
@section('title', 'Alertas')
@section('content')
<div class="section-title">Caducados ({{ $caducados->count() }})</div>

@forelse($caducados as $detalle)
<div class="card">
    <div class="card-left">
        <div class="icon-box">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="#4a4a4a" stroke-width="1.5"><path d="M21 8L12 3 3 8v8l9 5 9-5V8z"/><path d="M3 8l9 5 9-5"/><path d="M12 13v8"/></svg>
        </div>
        <span class="product-name">{{ $detalle->producto->nombre }}</span>
    </div>
    <span class="badge badge-caducado">Caducado</span>
</div>
@empty
<div class="card"><div class="card-left"><span class="product-name">No hay productos caducados</span></div></div>
@endforelse

<div class="section-title">Por vencer ({{ $porVencer->count() }})</div>

@forelse($porVencer as $detalle)
<div class="card">
    <div class="card-left">
        <div class="icon-box">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="#4a4a4a" stroke-width="1.5"><path d="M21 8L12 3 3 8v8l9 5 9-5V8z"/><path d="M3 8l9 5 9-5"/><path d="M12 13v8"/></svg>
        </div>
        <span class="product-name">{{ $detalle->producto->nombre }}</span>
    </div>
    <span class="badge badge-porvencer">{{ $detalle->dias_restantes }}d</span>
</div>
@empty
<div class="card"><div class="card-left"><span class="product-name">No hay productos por vencer</span></div></div>
@endforelse
@endsection
