<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTransaccionQrToPagosTable extends Migration
{
    public function up()
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->string('transaccion_qr')->nullable()->after('monto');
        });
    }

    public function down()
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->dropColumn('transaccion_qr');
        });
    }
}