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
        Schema::create('compras', function (Blueprint $table) {
            $table->id();
            $table->string('fecha');
            $table->string('hora');
            $table->decimal('total', 10, 2);
            $table->foreignId('detalle_pago_id')->constrained('detalle_pagos');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('metodo_pago_id')->constrained('metodo_pagos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compras');
    }
};
