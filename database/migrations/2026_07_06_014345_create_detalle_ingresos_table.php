<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detalle_ingresos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ingreso_id')->constrained('ingresos')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos');
            $table->decimal('cantidad_paquetes', 8, 2);
            $table->decimal('unidades_por_paquete', 8, 3);
            $table->decimal('cantidad_inicial', 8, 3);
            $table->decimal('stock_restante', 8, 3);
            $table->decimal('precio_paquete', 10, 2);
            $table->decimal('precio_costo_unitario', 10, 2);
            $table->date('fecha_vencimiento');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_ingresos');
    }
};
