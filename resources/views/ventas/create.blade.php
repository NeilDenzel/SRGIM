@extends('layouts.app')
@section('title', 'Nueva Venta')
@push('styles')
<style>
    .fila-venta { display:flex; gap:8px; margin-bottom:12px; flex-wrap:wrap; align-items:center; }
    .fila-venta select { flex:2; min-width:120px; padding:10px; }
    .fila-venta input { flex:1; min-width:80px; padding:10px; }
</style>
@endpush
@section('content')
<div class="barra-titulo">
    <h1>Nueva Venta</h1>
</div>

<form method="POST" action="{{ route('ventas.store') }}">
    @csrf

    <div id="productos-container">
        <div class="fila-venta" data-index="0">
            <select name="productos[0][detalle_ingreso_id]" required>
                <option value="">Seleccionar producto</option>
                @foreach($detallesIngreso as $d)
                <option value="{{ $d->id }}" data-precio="{{ $d->precio_costo_unitario }}" data-stock="{{ $d->stock_restante }}">
                    {{ $d->producto->nombre }} ({{ $d->producto->unidad_medida }}) — Stock: {{ number_format($d->stock_restante, 3) }} — Vence: {{ $d->fecha_vencimiento->format('d/m/Y') }}
                </option>
                @endforeach
            </select>
            <input type="number" step="0.001" name="productos[0][cantidad]" placeholder="Cantidad" min="0.001" required>
            <input type="number" step="0.01" name="productos[0][precio_venta]" placeholder="Precio unitario venta" min="0" step="0.01" required>
        </div>
    </div>

    <button type="button" class="btn btn-editar" onclick="agregarFila()" style="margin-bottom:20px;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        <span>Agregar otro producto</span>
    </button>

    <div class="form-acciones">
        <button type="submit" class="btn btn-guardar btn-full">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                <polyline points="17 21 17 13 7 13 7 21"></polyline>
                <polyline points="7 3 7 8 15 8"></polyline>
            </svg>
            <span>Registrar Venta</span>
        </button>
        <a href="{{ route('ventas.index') }}" class="btn-cancelar">Cancelar</a>
    </div>
</form>
@endsection

@push('scripts')
<script>
    let index = 1;

    function agregarFila() {
        const container = document.getElementById('productos-container');
        const original = container.querySelector('.fila-venta');
        if (!original) return;
        const div = original.cloneNode(true);
        div.dataset.index = index;
        div.querySelectorAll('select, input').forEach(el => {
            const name = el.getAttribute('name');
            if (name) el.setAttribute('name', name.replace(/\[\d+\]/, `[${index}]`));
            if (el.tagName === 'INPUT') el.value = '';
            if (el.tagName === 'SELECT') el.selectedIndex = 0;
        });
        const btn = div.querySelector('.btn-eliminar');
        if (!btn) {
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'btn btn-eliminar';
            removeBtn.style.cssText = 'padding:8px 12px;font-size:0.8rem;';
            removeBtn.textContent = '✕';
            removeBtn.onclick = () => div.remove();
            div.appendChild(removeBtn);
        }
        container.appendChild(div);
        index++;
    }

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-eliminar')) {
            const filas = document.querySelectorAll('.fila-venta');
            if (filas.length > 1) e.target.closest('.fila-venta').remove();
        }
    });
</script>
@endpush
