<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleIngreso extends Model
{
    protected $table = 'detalle_ingresos';

    protected $fillable = [
        'ingreso_id',
        'producto_id',
        'cantidad_paquetes',
        'unidades_por_paquete',
        'cantidad_inicial',
        'stock_restante',
        'precio_paquete',
        'precio_costo_unitario',
        'fecha_vencimiento',
    ];

    protected function casts(): array
    {
        return [
            'cantidad_paquetes' => 'decimal:2',
            'unidades_por_paquete' => 'decimal:3',
            'cantidad_inicial' => 'decimal:3',
            'stock_restante' => 'decimal:3',
            'precio_paquete' => 'decimal:2',
            'precio_costo_unitario' => 'decimal:2',
            'fecha_vencimiento' => 'date',
        ];
    }

    public function ingreso(): BelongsTo
    {
        return $this->belongsTo(Ingreso::class, 'ingreso_id');
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }
}
