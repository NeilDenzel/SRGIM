@extends('layouts.app')
@section('title', 'Caja')
@push('styles')
<style>
@media print {
    body { padding: 0 !important; }
    .app-header, .bottom-nav, .title-bar, .tabs, .btn-print, .no-print { display: none !important; }
    .chart-box { border: 1px solid #ccc; break-inside: avoid; }
    .reporte-cierre { break-inside: avoid; }
    .top-productos { break-inside: avoid; }
}
</style>
@endpush
@section('content')
<div class="title-bar">Cierre Ultimos 15/30 dias</div>

<main style="padding: 16px 0;">

    <!-- Cierre Diario (HU-14) -->
    <section class="reporte-cierre resumen-dia">
        <p class="fecha">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;margin-right:4px;">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
            {{ now()->locale('es')->isoFormat('dddd D [de] MMMM [del] YYYY') }}
        </p>
        <div class="reporte-grid">
            <div class="reporte-item reporte-ingreso">
                <span class="reporte-label">Ventas totales</span>
                <span class="reporte-valor">S/. {{ number_format($totalVentasHoy, 2) }}</span>
            </div>
            <div class="reporte-item reporte-costo">
                <span class="reporte-label">Costo de lo vendido</span>
                <span class="reporte-valor">S/. {{ number_format($costoVentasHoy, 2) }}</span>
            </div>
            <div class="reporte-item reporte-ganancia">
                <span class="reporte-label">Ganancia neta</span>
                <span class="reporte-valor">S/. {{ number_format($gananciaNetaHoy, 2) }}</span>
            </div>
        </div>
        <button onclick="window.print()" class="btn btn-editar btn-print" style="margin-top:12px;width:100%;justify-content:center;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="6 9 6 2 18 2 18 9"></polyline>
                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                <rect x="6" y="14" width="12" height="8"></rect>
            </svg>
            Imprimir Reporte
        </button>
    </section>

    <!-- Gráfico de Ventas -->
    <div class="tabs no-print">
        <a href="{{ route('caja.index', ['dias' => 15]) }}" class="{{ $dias == 15 ? 'activo' : '' }}">Últimos 15 días</a>
        <a href="{{ route('caja.index', ['dias' => 30]) }}" class="{{ $dias == 30 ? 'activo' : '' }}">Últimos 30 días</a>
    </div>

    <div class="chart-box">
        <svg viewBox="0 0 1080 300" preserveAspectRatio="xMidYMid meet">
            <g class="amount-labels">
                @foreach($datosGrafico as $i => $punto)
                @php
                    $cy = 200 - ($punto['total'] / $maxVenta * 150);
                    $cx = 40 + ($i * $espaciado);
                @endphp
                <text class="amount-label" x="{{ $cx }}" y="{{ $cy - 10 }}">
                    S/{{ number_format($punto['total'], 2) }}
                </text>
                @endforeach
            </g>
            <g>
                @foreach($datosGrafico as $i => $punto)
                <circle class="dot" cx="{{ 40 + ($i * $espaciado) }}" cy="{{ 200 - ($punto['total'] / $maxVenta * 150) }}" r="6"></circle>
                @endforeach
            </g>
            <g class="tick">
                @foreach($datosGrafico as $i => $punto)
                <circle cx="{{ 40 + ($i * $espaciado) }}" cy="200" r="1.4"></circle>
                @endforeach
            </g>
            <g>
                @foreach($datosGrafico as $i => $punto)
                <text class="date-label" x="{{ 40 + ($i * $espaciado) }}" y="220">{{ \Carbon\Carbon::parse($punto['fecha'])->format('d M') }}</text>
                @endforeach
            </g>
        </svg>
    </div>

    <!-- Top Productos (HU-15) -->
    @if($topProductos->isNotEmpty())
    <h2 class="subtitulo top-productos" style="margin-top:24px;">
        Productos más vendidos ({{ $dias }} días)
    </h2>
    <div class="top-productos">
        @foreach($topProductos as $i => $item)
        <article class="venta-card">
            <span class="top-posicion">#{{ $i + 1 }}</span>
            <span class="venta-nombre">{{ $item->producto_nombre }}</span>
            <div class="venta-precios">
                <span>Cant: {{ number_format($item->total_cantidad, 3) }}</span>
                <span>Venta: S/. {{ number_format($item->total_ingreso, 2) }}</span>
                <span>Ganancia: S/. {{ number_format($item->ganancia, 2) }}</span>
            </div>
        </article>
        @endforeach
    </div>
    @endif

</main>
@endsection
