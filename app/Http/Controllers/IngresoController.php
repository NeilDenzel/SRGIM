<?php

namespace App\Http\Controllers;

use App\Models\Ingreso;
use App\Models\DetalleIngreso;
use App\Models\Producto;
use App\Models\MovimientoInventario;
use Illuminate\Http\Request;

class IngresoController extends Controller
{
    public function index()
    {
        $ingresos = Ingreso::with('user', 'detalles.producto')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('ingresos.index', compact('ingresos'));
    }

    public function create(Request $request)
    {
        $productos = Producto::orderBy('nombre')->get();
        $productoId = $request->producto_id;

        return view('ingresos.create', compact('productos', 'productoId'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'productos' => 'required|array|min:1',
            'productos.*.producto_id' => 'required|exists:productos,id',
            'productos.*.cantidad_paquetes' => 'required|numeric|min:0.01',
            'productos.*.unidades_por_paquete' => 'required|numeric|min:0.001',
            'productos.*.precio_paquete' => 'required|numeric|min:0',
            'productos.*.fecha_vencimiento' => 'required|date',
        ]);

        $totalIngreso = 0;

        foreach ($data['productos'] as $item) {
            $totalIngreso += $item['precio_paquete'] * $item['cantidad_paquetes'];
        }

        $ingreso = Ingreso::create([
            'user_id' => auth()->id(),
            'total_ingreso' => $totalIngreso,
        ]);

        foreach ($data['productos'] as $item) {
            $cantidadPaquetes = (float) $item['cantidad_paquetes'];
            $unidadesPorPaquete = (float) $item['unidades_por_paquete'];
            $precioPaquete = (float) $item['precio_paquete'];
            $cantidadInicial = $cantidadPaquetes * $unidadesPorPaquete;
            $precioUnitario = $unidadesPorPaquete > 0 ? round($precioPaquete / $unidadesPorPaquete, 2) : 0;

            DetalleIngreso::create([
                'ingreso_id' => $ingreso->id,
                'producto_id' => $item['producto_id'],
                'cantidad_paquetes' => $cantidadPaquetes,
                'unidades_por_paquete' => $unidadesPorPaquete,
                'cantidad_inicial' => $cantidadInicial,
                'stock_restante' => $cantidadInicial,
                'precio_paquete' => $precioPaquete,
                'precio_costo_unitario' => $precioUnitario,
                'fecha_vencimiento' => $item['fecha_vencimiento'],
            ]);

            $producto = Producto::findOrFail($item['producto_id']);
            $producto->increment('stock_total', $cantidadInicial);

            MovimientoInventario::create([
                'producto_id' => $item['producto_id'],
                'user_id' => auth()->id(),
                'tipo' => 'ingreso',
                'cantidad' => $cantidadInicial,
            ]);
        }

        return redirect()->route('ingresos.show', $ingreso)
            ->with('success', 'Ingreso registrado correctamente.');
    }

    public function show(Ingreso $ingreso)
    {
        $ingreso->load('user', 'detalles.producto');
        return view('ingresos.show', compact('ingreso'));
    }
}
