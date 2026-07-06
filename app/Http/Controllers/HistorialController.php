<?php

namespace App\Http\Controllers;

use App\Models\MovimientoInventario;

class HistorialController extends Controller
{
    public function index()
    {
        $movimientos = MovimientoInventario::with('producto')
            ->orderBy('created_at', 'desc')
            ->limit(100)
            ->get();

        return view('historial.index', compact('movimientos'));
    }
}
