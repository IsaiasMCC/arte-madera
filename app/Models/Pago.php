<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pagos';
    protected $fillable = ['fecha', 'hora', 'monto', 'tipo_pago', 'pedido_id'];

    public function detallePagos()
    {
        return $this->hasMany(DetallePago::class);
    }

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }
}
