<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\DetalleIngreso;
use App\Models\Producto;
use App\Models\MovimientoInventario;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    public function index()
    {
        $ventasHoy = Venta::with('detalles.producto')
            ->whereDate('created_at', today())
            ->where('anulada', false)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalHoy = $ventasHoy->sum('total_venta');

        $ventasAnuladasHoy = Venta::with('detalles.producto')
            ->whereDate('created_at', today())
            ->where('anulada', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('ventas.index', compact('ventasHoy', 'totalHoy', 'ventasAnuladasHoy'));
    }

    public function create()
    {
        $detallesIngreso = DetalleIngreso::with('producto')
            ->where('stock_restante', '>', 0)
            ->where('fecha_vencimiento', '>=', now()->startOfDay())
            ->orderBy('fecha_vencimiento')
            ->get();

        return view('ventas.create', compact('detallesIngreso'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'productos' => 'required|array|min:1',
            'productos.*.detalle_ingreso_id' => 'required|exists:detalle_ingresos,id',
            'productos.*.cantidad' => 'required|numeric|min:0.001',
            'productos.*.precio_venta' => 'required|numeric|min:0',
        ]);

        $totalVenta = 0;

        foreach ($data['productos'] as $item) {
            $detalleIngreso = DetalleIngreso::findOrFail($item['detalle_ingreso_id']);
            $cantidad = (float) $item['cantidad'];

            if ($cantidad > $detalleIngreso->stock_restante) {
                return back()->with('error', "Stock insuficiente para {$detalleIngreso->producto->nombre}. Disponible: " . number_format($detalleIngreso->stock_restante, 3));
            }

            $totalVenta += (float) $item['precio_venta'] * $cantidad;
        }

        $venta = Venta::create([
            'user_id' => auth()->id(),
            'total_venta' => $totalVenta,
        ]);

        foreach ($data['productos'] as $item) {
            $detalleIngreso = DetalleIngreso::findOrFail($item['detalle_ingreso_id']);
            $cantidad = (float) $item['cantidad'];
            $precioVenta = (float) $item['precio_venta'];

            DetalleVenta::create([
                'venta_id' => $venta->id,
                'producto_id' => $detalleIngreso->producto_id,
                'detalle_ingreso_id' => $detalleIngreso->id,
                'cantidad' => $cantidad,
                'precio_venta_unitario' => $precioVenta,
                'subtotal' => $precioVenta * $cantidad,
            ]);

            $detalleIngreso->decrement('stock_restante', $cantidad);

            $producto = Producto::findOrFail($detalleIngreso->producto_id);
            $producto->decrement('stock_total', $cantidad);

            MovimientoInventario::create([
                'producto_id' => $detalleIngreso->producto_id,
                'user_id' => auth()->id(),
                'tipo' => 'venta',
                'cantidad' => -$cantidad,
            ]);
        }

        return redirect()->route('ventas.index')
            ->with('success', 'Venta registrada correctamente.');
    }

    public function destroy(Venta $venta)
    {
        if (!$venta->created_at->isToday()) {
            return back()->with('error', 'Solo se pueden anular ventas del día de hoy.');
        }

        if ($venta->anulada) {
            return back()->with('error', 'Esta venta ya fue anulada.');
        }

        foreach ($venta->detalles as $detalle) {
            $detalleIngreso = DetalleIngreso::find($detalle->detalle_ingreso_id);
            if ($detalleIngreso) {
                $detalleIngreso->increment('stock_restante', (float) $detalle->cantidad);
            }

            $producto = Producto::find($detalle->producto_id);
            if ($producto) {
                $producto->increment('stock_total', (float) $detalle->cantidad);
            }

            MovimientoInventario::create([
                'producto_id' => $detalle->producto_id,
                'user_id' => auth()->id(),
                'tipo' => 'anulacion',
                'cantidad' => (float) $detalle->cantidad,
            ]);
        }

        $venta->update(['anulada' => true]);

        return redirect()->route('ventas.index')
            ->with('success', 'Venta anulada correctamente. Stock restaurado.');
    }
}
