<?php

namespace Database\Seeders;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\DetalleIngreso;
use App\Models\Producto;
use App\Models\MovimientoInventario;
use Illuminate\Database\Seeder;

class VentasPruebaSeeder extends Seeder
{
    public function run(): void
    {
        $ventas = [
            [
                'created_at' => '2026-07-09 10:30:00',
                'user_id' => 1,
                'items' => [
                    ['detalle_ingreso_id' => 1, 'producto_id' => 1, 'cantidad' => 2, 'precio_venta' => 12.50],
                    ['detalle_ingreso_id' => 4, 'producto_id' => 4, 'cantidad' => 1, 'precio_venta' => 12.50],
                ],
            ],
            [
                'created_at' => '2026-07-10 11:15:00',
                'user_id' => 1,
                'items' => [
                    ['detalle_ingreso_id' => 2, 'producto_id' => 2, 'cantidad' => 1, 'precio_venta' => 22.50],
                    ['detalle_ingreso_id' => 3, 'producto_id' => 3, 'cantidad' => 2, 'precio_venta' => 11.25],
                ],
            ],
            [
                'created_at' => '2026-07-11 09:45:00',
                'user_id' => 1,
                'items' => [
                    ['detalle_ingreso_id' => 6, 'producto_id' => 5, 'cantidad' => 1, 'precio_venta' => 18.00],
                    ['detalle_ingreso_id' => 5, 'producto_id' => 6, 'cantidad' => 1, 'precio_venta' => 15.00],
                ],
            ],
        ];

        foreach ($ventas as $v) {
            $total = 0;
            foreach ($v['items'] as $item) {
                $total += $item['cantidad'] * $item['precio_venta'];
            }

            $venta = Venta::create([
                'user_id' => $v['user_id'],
                'total_venta' => $total,
            ]);

            $venta->created_at = $v['created_at'];
            $venta->updated_at = $v['created_at'];
            $venta->save();

            foreach ($v['items'] as $item) {
                $subtotal = $item['cantidad'] * $item['precio_venta'];

                DetalleVenta::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $item['producto_id'],
                    'cantidad' => $item['cantidad'],
                    'precio_venta_unitario' => $item['precio_venta'],
                    'subtotal' => $subtotal,
                ]);

                $detalleIngreso = DetalleIngreso::find($item['detalle_ingreso_id']);
                $detalleIngreso->decrement('stock_restante', $item['cantidad']);

                $producto = Producto::find($item['producto_id']);
                $producto->decrement('stock_total', $item['cantidad']);

                MovimientoInventario::create([
                    'producto_id' => $item['producto_id'],
                    'user_id' => $v['user_id'],
                    'tipo' => 'venta',
                    'cantidad' => -$item['cantidad'],
                ]);
            }

            $this->command->info("Venta del {$v['created_at']} creada — Total: S/{$total}");
        }
    }
}
