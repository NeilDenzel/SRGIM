@extends('layouts.app')
@section('title', 'Caja')
@section('content')
<div class="title-bar">Cierre Diario</div>

<main style="padding: 16px 0;">
    <div class="tabs">
        <span>Últimos 15 dias</span>
        <span>Últimos 30 dias</span>
    </div>

    <div class="chart-box">
        <svg viewBox="0 0 1080 260" preserveAspectRatio="xMidYMid meet">
            <g>
                @foreach($datosGrafico as $i => $punto)
                <circle class="dot" cx="{{ 40 + ($i * 72) }}" cy="{{ 200 - ($punto['total'] / $maxVenta * 150) }}" r="6"></circle>
                @endforeach
            </g>
            <g class="tick">
                @foreach($datosGrafico as $i => $punto)
                <circle cx="{{ 40 + ($i * 72) }}" cy="200" r="1.4"></circle>
                @endforeach
            </g>
            <g>
                @foreach($datosGrafico as $i => $punto)
                <text class="date-label" x="{{ 40 + ($i * 72) }}" y="220">{{ \Carbon\Carbon::parse($punto['fecha'])->format('d M') }}</text>
                @endforeach
            </g>
        </svg>
    </div>
</main>
@endsection
