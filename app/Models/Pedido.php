<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha',
        'hora',
        'total',
        'estado',
        'user_id',
        'tienda_id',
    ];

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'pedido_productos')
            ->withPivot('cantidad')
            ->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function detalles()
    {
        return $this->hasMany(DetallePedido::class, 'pedido_id');
    }

    public function envio()
    {
        return $this->hasOne(Envio::class);
    }

    public function pago()
    {
        return $this->hasOne(Pago::class, 'pedido_id');
    }

    public function totalPagado()
    {
        return $this->pago()->with('detallePagos')
            ->get()
            ->sum(function ($pago) {
                return $pago->detallePagos->sum('monto');
            });
    }

    public function saldoPendiente()
    {
        return $this->total - $this->totalPagado();
    }

    public function tienda()
    {
        return $this->belongsTo(Tienda::class, 'tienda_id');
    }
}
