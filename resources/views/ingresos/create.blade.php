@extends('layouts.app')
@section('title', 'Agregar Stock')
@push('styles')
<style>
    .fila-producto {
        display:flex; gap:8px; margin-bottom:14px; flex-wrap:wrap; align-items:center;
        border:3px solid var(--borde); border-radius:var(--border-radius-sm);
        padding:14px; background:#f7f9fa;
    }
    .fila-producto select, .fila-producto input { flex:1; min-width:90px; padding:10px; }
    .fila-producto .lbl-ump { font-size:1.1rem; font-weight:700; color:var(--texto-gris); padding:0 4px; }
    .precio-unitario, .stock-calculo { font-size:0.85rem; color:var(--texto-gris); font-weight:600; white-space:nowrap; }
</style>
@endpush
@section('content')
<div class="barra-titulo">
    <h1>Agregar Stock</h1>
</div>

<form method="POST" action="{{ route('ingresos.store') }}">
    @csrf

    <div id="productos-container">
        <div class="fila-producto" data-index="0">
            <select name="productos[0][producto_id]" required style="min-width:140px;">
                <option value="">Seleccionar producto</option>
                @foreach($productos as $p)
                <option value="{{ $p->id }}" data-um="{{ $p->unidad_medida }}" {{ $productoId == $p->id ? 'selected' : '' }}>
                    {{ $p->nombre }} ({{ $p->unidad_medida }})
                </option>
                @endforeach
            </select>
            <input type="number" step="0.01" name="productos[0][cantidad_paquetes]" placeholder="Cant. paquetes" min="0.01" required oninput="calcFila(0)">
            <span class="lbl-ump">×</span>
            <input type="number" step="0.001" name="productos[0][unidades_por_paquete]" placeholder="Unid. x paquete" min="0.001" required oninput="calcFila(0)">
            <span class="stock-calculo" id="calculo-0">= 0</span>
            <input type="number" step="0.01" name="productos[0][precio_paquete]" placeholder="S/ x paquete" min="0" required oninput="calcFila(0)">
            <span class="precio-unitario" id="unitario-0">—</span>
            <input type="date" name="productos[0][fecha_vencimiento]" required>
            <button type="button" class="btn btn-eliminar" onclick="this.parentElement.remove()" style="padding:8px 12px;font-size:0.8rem;">✕</button>
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
            <span>Registrar Ingreso</span>
        </button>
        <a href="{{ $productoId ? route('catalogos.index') : route('dashboard') }}" class="btn-cancelar">Cancelar</a>
    </div>
</form>
@endsection

@push('scripts')
<script>
    let idx = 1;
    const productos = @json($productos->map(fn($p) => ['id' => $p->id, 'nombre' => $p->nombre, 'um' => $p->unidad_medida]));

    function agregarFila() {
        const container = document.getElementById('productos-container');
        const div = document.createElement('div');
        div.className = 'fila-producto';
        div.dataset.index = idx;

        let opts = '<option value="">Seleccionar producto</option>';
        productos.forEach(p => {
            opts += `<option value="${p.id}" data-um="${p.um}">${p.nombre} (${p.um})</option>`;
        });

        div.innerHTML = `
            <select name="productos[${idx}][producto_id]" required style="min-width:140px;">${opts}</select>
            <input type="number" step="0.01" name="productos[${idx}][cantidad_paquetes]" placeholder="Cant. paquetes" min="0.01" required oninput="calcFila(${idx})">
            <span class="lbl-ump">×</span>
            <input type="number" step="0.001" name="productos[${idx}][unidades_por_paquete]" placeholder="Unid. x paquete" min="0.001" required oninput="calcFila(${idx})">
            <span class="stock-calculo" id="calculo-${idx}">= 0</span>
            <input type="number" step="0.01" name="productos[${idx}][precio_paquete]" placeholder="S/ x paquete" min="0" required oninput="calcFila(${idx})">
            <span class="precio-unitario" id="unitario-${idx}">—</span>
            <input type="date" name="productos[${idx}][fecha_vencimiento]" required>
            <button type="button" class="btn btn-eliminar" onclick="this.parentElement.remove()" style="padding:8px 12px;font-size:0.8rem;">✕</button>
        `;
        container.appendChild(div);
        idx++;
    }

    function calcFila(i) {
        const fila = document.querySelector(`.fila-producto[data-index="${i}"]`);
        if (!fila) return;
        const paq = parseFloat(fila.querySelector('[name$="[cantidad_paquetes]"]')?.value) || 0;
        const ump = parseFloat(fila.querySelector('[name$="[unidades_por_paquete]"]')?.value) || 0;
        const pp = parseFloat(fila.querySelector('[name$="[precio_paquete]"]')?.value) || 0;
        const sel = fila.querySelector('select');
        const um = sel?.selectedOptions?.[0]?.dataset?.um || 'UN';

        const total = paq * ump;
        document.getElementById(`calculo-${i}`).textContent = `= ${total.toFixed(3)} ${um}`;

        const unitario = (ump > 0) ? (pp / ump) : 0;
        document.getElementById(`unitario-${i}`).textContent = unitario > 0 ? `S/. ${unitario.toFixed(2)} c/${um}` : '—';
    }
</script>
@endpush
