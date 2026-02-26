<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = [
        'titulo',
        'descripcion',
        'id_usuario_creador',
        'id_usuario_asignado',
        'id_subcategoria',
        'id_prioridad',
        'id_estado',
        'id_archivo',
        'id_archivo_respuesta'
    ];

      public function creador()
    {
        return $this->belongsTo(User::class, 'id_usuario_creador');
    }

    public function archivo()
    {
        return $this->belongsTo(Archivo::class, 'id_archivo');
    }

    public function archivoRespuesta()
    {
        return $this->belongsTo(Archivo::class, 'id_archivo_respuesta');
    }

    public function subcategoria()
    {
        return $this->belongsTo(subcategoria::class, 'id_subcategoria');
    }

    public function prioridad()
    {
        return $this->belongsTo(prioridad::class, 'id_prioridad');
    }

    public function estado()
    {
        return $this->belongsTo(estado::class, 'id_estado');
    }

    // Relación con el usuario al que se asignó el ticket
    public function asignado()
    {
        return $this->belongsTo(User::class, 'id_usuario_asignado');
    }
}
