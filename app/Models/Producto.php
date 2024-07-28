<?php

// app/Models/Producto.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_producto',
        'codigo_barras',
        'descripcion',
        'nombre_producto',
        'categoria',
        'id_familiar',
        'id_linea_web',
        'stock',
        'precio_publico',
        'precio_cliente',
        'cantidad',
        'descuento',
        'img',
    ];
}
