<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetallePago extends Model
{
    protected $table = 'detalle_pagos';
    protected $fillable = ['fecha', 'hora', 'monto', 'saldo', 'pago_id'];

    public function Pago()
    {
        return $this->belongsTo(Pago::class, 'pago_id');
    }
}
