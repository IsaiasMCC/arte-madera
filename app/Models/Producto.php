<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';
    protected $fillable = ['nombre', 'descripcion', 'precio', 'stock', 'foto', 'estado', 'categoria_id', 'tienda_id'];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function tienda()
    {
        return $this->belongsTo(Tienda::class);
    }
}
