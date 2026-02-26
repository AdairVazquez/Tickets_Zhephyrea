<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Imagen extends Model
{
    protected $table = 'imagenes';
    protected $fillable = [
        'nombre',
        'descripcion',
        'ruta_archivo',
        'tipo',
        'fecha_subida',
        'estado',
    ];
}
