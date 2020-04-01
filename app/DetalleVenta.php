<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    protected $table = 'detalle_ventas';
    protected $fillable = [
        'idventa',
        'idarticulo',
        'leyenda1',
        'leyenda2',
        'leyenda3',
        'leyenda4',
        'cantidad',
        'precio'
    ];
}
