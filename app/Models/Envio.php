<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Envio extends Model
{
    protected $table = 'envios';
    protected $fillable = ['direccion', 'ciudad', 'estado', 'codigo_postal', 'pedido_id'];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }
    
}