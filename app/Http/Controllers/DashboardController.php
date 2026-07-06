<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\DetalleIngreso;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = Producto::with('categoria');

        if ($buscar = $request->buscar) {
            $query->where('nombre', 'like', "%{$buscar}%");
        }

        $productos = $query->orderBy('nombre')->get();

        $porVencer = DetalleIngreso::where('stock_restante', '>', 0)
            ->whereBetween('fecha_vencimiento', [Carbon::today(), Carbon::today()->addDays(7)])
            ->count();

        return view('dashboard.index', compact('productos', 'porVencer'));
    }
}
