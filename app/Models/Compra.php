<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $table = 'compras';
    protected $fillable = ['fecha', 'hora', 'total', 'detalle_pago_id', 'user_id', 'metodo_pago_id'];
}