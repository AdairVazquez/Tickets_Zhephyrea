<?php

namespace App\Livewire\Admin;
use Illuminate\Support\Facades\Storage;
use App\Models\Imagen;
use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;

class Imagenes extends Component
{
    public $mostrarFormulario = false;
    public $modoFormulario = 'crear';
    public $imagenSeleccionadaId = null;
    public $postIdDel;

    protected $listeners = ['deletePost'];

    public function crearImagen()
    {
        $this->mostrarFormulario = true;
        $this->modoFormulario = 'crear';
        $this->imagenSeleccionadaId = null;
        $this->dispatch('crearImagen');
    }

    public function editarImagen($id)
    {
        $this->mostrarFormulario = true;
        $this->modoFormulario = 'editar';
        $this->imagenSeleccionadaId = $id;
        $this->dispatch('editarImagen', id: $id);
    }

    public function confirmDelete($id)
    {
        $this->postIdDel = $id;
        $this->dispatch('show-delete-confirmation');
    }

    public function deletePost()
    {
        $imagen = Imagen::find($this->postIdDel);

        if (!$imagen) {
            $this->dispatch('imagenEliminadaError');
            return;
        }

        if (!empty($imagen->ruta_archivo) && Storage::disk('public')->exists($imagen->ruta_archivo)) {
            Storage::disk('public')->delete($imagen->ruta_archivo);
        }

        $imagen->delete();
        $this->dispatch('imagenEliminada');
    }

    public function cerrarFormularioImagen()
    {
        $this->mostrarFormulario = false;
        $this->modoFormulario = 'crear';
        $this->imagenSeleccionadaId = null;
    }

    #[On('imagenGuardada')]
    public function imagenGuardada()
    {
        $this->cerrarFormularioImagen();
    }

    public function render()
    {
        return view('livewire.admin.imagenes', [
            'imagenes' => Imagen::orderBy('id', 'desc')->paginate(10),
        ]);
    }
}
