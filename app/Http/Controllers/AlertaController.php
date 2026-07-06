<?php

namespace App\Http\Controllers;

use App\Models\DetalleIngreso;
use Carbon\Carbon;

class AlertaController extends Controller
{
    public function index()
    {
        $hoy = Carbon::today();

        $caducados = DetalleIngreso::with('producto')
            ->where('stock_restante', '>', 0)
            ->where('fecha_vencimiento', '<', $hoy)
            ->get()
            ->map(function ($d) use ($hoy) {
                $d->dias_restantes = $hoy->diffInDays($d->fecha_vencimiento, false);
                return $d;
            });

        $porVencer = DetalleIngreso::with('producto')
            ->where('stock_restante', '>', 0)
            ->whereBetween('fecha_vencimiento', [$hoy, $hoy->copy()->addDays(7)])
            ->get()
            ->map(function ($d) use ($hoy) {
                $d->dias_restantes = $hoy->diffInDays($d->fecha_vencimiento);
                return $d;
            });

        return view('alertas.index', compact('caducados', 'porVencer'));
    }
}
