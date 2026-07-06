<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use Carbon\Carbon;

class CajaController extends Controller
{
    public function index()
    {
        $dias = 15;
        $datosGrafico = [];
        $maxVenta = 1;

        for ($i = $dias - 1; $i >= 0; $i--) {
            $fecha = Carbon::today()->subDays($i);
            $total = Venta::whereDate('created_at', $fecha)->sum('total_venta');
            $datosGrafico[] = [
                'fecha' => $fecha->format('Y-m-d'),
                'total' => (float) $total,
            ];
            if ($total > $maxVenta) $maxVenta = $total;
        }

        return view('caja.index', compact('datosGrafico', 'maxVenta'));
    }
}
