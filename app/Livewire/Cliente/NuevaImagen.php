<?php

namespace App\Livewire\Cliente;

use App\Models\Imagen;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NuevaImagen extends Component
{
    use WithFileUploads;
    public $nombre, $descripcion, $ruta_archivo, $fecha_subida;

    public function save()
    {
        $this->validate([
            'nombre' => 'required',
            'ruta_archivo' => 'required|file|mimes:jpg,jpeg,png,gif,svg|max:2048',
        ],[
            'nombre.required'=> 'Campo obligatorio, escribe un nombre',
            'ruta_archivo.required' => 'Debes subir un archivo',
            'ruta_archivo.file' => 'El campo debe ser un archivo válido',
            'ruta_archivo.mimes' => 'Solo se permiten archivos de imagen (jpg, jpeg, png, gif, svg)',
            'ruta_archivo.max' => 'El archivo no debe superar los 2MB',
        ]);

        $this->fecha_subida = now();

        $ruta = $this->ruta_archivo->store('imagenes', 'public');

        Imagen::create([
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'ruta_archivo' => $ruta,
            'tipo' => $this->ruta_archivo->getMimeType(),
            'fecha_subida' => $this->fecha_subida,
        ]);

        $this->reset(['nombre', 'descripcion', 'ruta_archivo']);
        session()->flash('mensaje', 'Imagen subida correctamente');
    }

    public function mount(){

    }

    public function render()
    {
        return view('livewire.cliente.nueva-imagen');
    } 
}
