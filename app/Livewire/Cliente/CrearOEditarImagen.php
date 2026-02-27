<?php

namespace App\Livewire\Cliente;

use App\Models\Estado;
use App\Models\Imagen;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class CrearOEditarImagen extends Component
{
    use WithFileUploads;

    public $nombre, $descripcion, $ruta_archivo, $fecha_subida;
    public $imagen_antigua, $imagen_id;
    public $modo = 'crear';
    public $estados = [];
    public $estado_id;

    public function mount()
    {
        $this->limpiarCampos();
    }

    public function save()
    {
        if (!empty($this->imagen_id)) {
            $this->actualizarImagen();
            return;
        }

        $this->crearNuevaImagen();
    }

    protected function crearNuevaImagen()
    {
        $this->validate([
            'nombre' => 'required',
            'ruta_archivo' => 'required|file|mimes:jpg,jpeg,png,gif,svg|max:2048',
            'estado_id' => 'required|exists:estados,id'
        ], [
            'nombre.required' => 'Campo obligatorio, escribe un nombre',
            'ruta_archivo.required' => 'Debes subir un archivo',
            'ruta_archivo.file' => 'El campo debe ser un archivo valido',
            'ruta_archivo.mimes' => 'Solo se permiten archivos de imagen (jpg, jpeg, png, gif, svg)',
            'ruta_archivo.max' => 'El archivo no debe superar los 2MB',
            'estado_id.required' => 'Es obligatorio el estado',
            'estado_id.exists' => 'Elige un estado existente',
        ]);

        $this->fecha_subida = now();
        $ruta = $this->ruta_archivo->store('imagenes', 'public');

        Imagen::create([
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'ruta_archivo' => $ruta,
            'tipo' => $this->ruta_archivo->getMimeType(),
            'fecha_subida' => $this->fecha_subida,
            'estado_id' => $this->estado_id,
        ]);

        $this->dispatch('imagenGuardada')->to(\App\Livewire\Admin\Imagenes::class);
        session()->flash('mensaje', 'Imagen subida correctamente');
        $this->limpiarCampos();
    }

    public function actualizarImagen()
    {
        $this->validate([
            'nombre' => 'required',
            'ruta_archivo' => 'nullable|file|mimes:jpg,jpeg,png,gif,svg|max:2048',
            'estado_id' => 'required|exists:estados,id'
        ], [
            'nombre.required' => 'Campo obligatorio, escribe un nombre',
            'ruta_archivo.file' => 'El campo debe ser un archivo valido',
            'ruta_archivo.mimes' => 'Solo se permiten archivos de imagen (jpg, jpeg, png, gif, svg)',
            'ruta_archivo.max' => 'El archivo no debe superar los 2MB',
            'estado_id.required' => 'Es obligatorio el estado',
            'estado_id.exists' => 'Elige un estado existente',
        ]);

        $imagen = Imagen::find($this->imagen_id);

        if (!$imagen) {
            session()->flash('mensaje', 'La imagen a editar no existe');
            return;
        }

        $data = [
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'estado_id' => $this->estado_id,
        ];

        if ($this->ruta_archivo) {
            $nuevaRuta = $this->ruta_archivo->store('imagenes', 'public');
            $data['ruta_archivo'] = $nuevaRuta;
            $data['tipo'] = $this->ruta_archivo->getMimeType();
            $this->imagen_antigua = $nuevaRuta;

            if (!empty($imagen->ruta_archivo) && Storage::disk('public')->exists($imagen->ruta_archivo)) {
                Storage::disk('public')->delete($imagen->ruta_archivo);
            }
        }

        $imagen->update($data);

        $this->dispatch('imagenGuardada')->to(\App\Livewire\Admin\Imagenes::class);
        session()->flash('mensaje', 'Imagen actualizada correctamente');
        $this->limpiarCampos();
    }

    public function limpiarCampos()
    {
        $this->modo = 'crear';
        $this->estados = Estado::whereIn('id', [1, 3])->get();
        $this->reset(['nombre', 'descripcion', 'ruta_archivo', 'fecha_subida', 'imagen_id', 'imagen_antigua', 'estado_id']);
        $this->resetValidation();
    }

    public function cargarImagenParaEditar($id)
    {
        $imagen = Imagen::find($id);

        if ($imagen) {
            $this->modo = 'editar';
            $this->imagen_id = $imagen->id;
            $this->nombre = $imagen->nombre;
            $this->descripcion = $imagen->descripcion;
            $this->fecha_subida = $imagen->fecha_subida;
            $this->imagen_antigua = $imagen->ruta_archivo;
            $this->ruta_archivo = null;
            $this->estado_id = $imagen->estado_id;
        }
    }

    #[On('crearImagen')]
    public function crearImagen()
    {
        $this->limpiarCampos();
    }

    #[On('editarImagen')]
    public function editarImagen($id)
    {
        $this->estados = Estado::whereIn('id', [1, 3])->get();
        $this->cargarImagenParaEditar($id);
    }

    #[On('eliminarImagen')]
    public function eliminarImagen($id)
    {
        $imagen = Imagen::find($id);

        if (!$imagen) {
            $this->dispatch('imagenEliminadaError');
            return;
        }

        if (!empty($imagen->ruta_archivo) && Storage::disk('public')->exists($imagen->ruta_archivo)) {
            Storage::disk('public')->delete($imagen->ruta_archivo);
        }

        $imagen->delete();

        $this->dispatch('imagenEliminada');
        $this->dispatch('imagenGuardada')->to(\App\Livewire\Admin\Imagenes::class);
        session()->flash('mensaje', 'Imagen eliminada correctamente');
    }

    public function render()
    {
        return view('livewire.cliente.crear-o-editar-imagen');
    }
}
