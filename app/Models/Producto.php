<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Producto extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'categoria_id',
        'nombre',
        'foto_ruta',
        'unidad_medida',
        'stock_total',
    ];

    protected function casts(): array
    {
        return [
            'stock_total' => 'decimal:3',
        ];
    }

    protected function stockFormateado(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->unidad_medida === 'UN'
                ? (string) round($this->stock_total)
                : number_format($this->stock_total, 3, '.', '')
        );
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function detallesIngreso(): HasMany
    {
        return $this->hasMany(DetalleIngreso::class);
    }

    public function detallesVenta(): HasMany
    {
        return $this->hasMany(DetalleVenta::class);
    }

    public function movimientos(): HasMany
    {
        return $this->hasMany(MovimientoInventario::class);
    }
}
