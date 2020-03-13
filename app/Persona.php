<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $fillable = [
        'nombre','apellido','tipo_documento','num_documento','email','telefono','direccion','razon_social','nombre_empresa'
    ];

    public function user(){
        return $this->hasOne('App\User');
    }

}
