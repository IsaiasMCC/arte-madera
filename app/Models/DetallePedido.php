<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    protected $table = 'detalle_pedidos';
    protected $fillable = ['cantidad', 'subtotal', 'pedido_id', 'producto_id'];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
