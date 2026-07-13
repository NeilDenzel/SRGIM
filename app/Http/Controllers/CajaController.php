<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CajaController extends Controller
{
    public function index(Request $request)
    {
        $dias = max(2, min((int) $request->get('dias', 15), 30));
        $datosGrafico = [];
        $maxVenta = 0;

        for ($i = $dias - 1; $i >= 0; $i--) {
            $fecha = Carbon::today()->subDays($i);
            $total = Venta::whereDate('created_at', $fecha)
                ->where('anulada', false)
                ->sum('total_venta');
            $datosGrafico[] = [
                'fecha' => $fecha->format('Y-m-d'),
                'total' => (float) $total,
            ];
            if ($total > $maxVenta) $maxVenta = $total;
        }

        if ($maxVenta === 0) $maxVenta = 1;
        $espaciado = $dias > 1 ? (int) floor(980 / ($dias - 1)) : 70;

        // HU-14: Cierre diario
        $ventasHoy = Venta::whereDate('created_at', today())
            ->where('anulada', false)
            ->get();
        $totalVentasHoy = $ventasHoy->sum('total_venta');
        $costoVentasHoy = DetalleVenta::whereIn('venta_id', $ventasHoy->pluck('id'))
            ->whereNotNull('detalle_ingreso_id')
            ->join('detalle_ingresos', 'detalle_ventas.detalle_ingreso_id', '=', 'detalle_ingresos.id')
            ->select(DB::raw('SUM(detalle_ventas.cantidad * detalle_ingresos.precio_costo_unitario) as total_costo'))
            ->value('total_costo') ?? 0;
        $gananciaNetaHoy = $totalVentasHoy - (float) $costoVentasHoy;

        // HU-15: Top productos del período
        $fechaInicio = Carbon::today()->subDays($dias - 1);
        $topProductos = DetalleVenta::whereIn('venta_id', function ($q) use ($fechaInicio) {
                $q->select('id')->from('ventas')
                    ->where('anulada', false)
                    ->whereDate('created_at', '>=', $fechaInicio);
            })
            ->select(
                'producto_id',
                DB::raw('SUM(cantidad) as total_cantidad'),
                DB::raw('SUM(subtotal) as total_ingreso')
            )
            ->groupBy('producto_id')
            ->orderByDesc('total_cantidad')
            ->limit(10)
            ->with('producto')
            ->get()
            ->map(function ($item) {
                $costoUnitario = DB::table('detalle_ingresos')
                    ->where('producto_id', $item->producto_id)
                    ->where('stock_restante', '>', 0)
                    ->orderBy('fecha_vencimiento')
                    ->value('precio_costo_unitario') ?? 0;
                $item->costo_total = (float) $item->total_cantidad * (float) $costoUnitario;
                $item->ganancia = (float) $item->total_ingreso - $item->costo_total;
                $item->producto_nombre = $item->producto->nombre ?? 'Desconocido';
                return $item;
            });

        return view('caja.index', compact(
            'datosGrafico', 'maxVenta', 'dias', 'espaciado',
            'totalVentasHoy', 'costoVentasHoy', 'gananciaNetaHoy',
            'topProductos'
        ));
    }
}
