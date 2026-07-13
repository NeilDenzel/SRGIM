<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->boolean('anulada')->default(false)->after('total_venta');
        });

        Schema::table('detalle_ventas', function (Blueprint $table) {
            $table->foreignId('detalle_ingreso_id')->nullable()->constrained('detalle_ingresos')->nullOnDelete()->after('producto_id');
        });
    }

    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropColumn('anulada');
        });

        Schema::table('detalle_ventas', function (Blueprint $table) {
            $table->dropForeign(['detalle_ingreso_id']);
            $table->dropColumn('detalle_ingreso_id');
        });
    }
};
