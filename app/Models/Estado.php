<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Estado extends Model
{

    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';   

    protected $fillable = [
        'nombre_estado'
    ];

    public function imagen(){
        return $this->hasMany(Imagen::class);
    }
}
