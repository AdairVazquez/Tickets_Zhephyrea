<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Imagen;
use Livewire\Attributes\On;

class Imagenes extends Component
{
    use WithPagination;

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

        $this->dispatch('crearImagen')->to(\App\Livewire\Cliente\CrearOEditarImagen::class);
    }

    public function editarImagen($id)
    {
        $this->mostrarFormulario = true;
        $this->modoFormulario = 'editar';
        $this->imagenSeleccionadaId = $id;

        $this->dispatch('editarImagen', id: $id)->to(\App\Livewire\Cliente\CrearOEditarImagen::class);
    }

    public function confirmDelete($id)
    {
        $this->postIdDel = $id;
        $this->dispatch('show-delete-confirmation');
    }

    public function deletePost()
    {
        if (empty($this->postIdDel)) {
            $this->dispatch('imagenEliminadaError');
            return;
        }

        $this->dispatch('eliminarImagen', id: $this->postIdDel)->to(\App\Livewire\Cliente\CrearOEditarImagen::class);
        $this->postIdDel = null;
    }

    public function cerrarFormularioImagen()
    {
        $this->mostrarFormulario = false;
        $this->modoFormulario = 'crear';
        $this->imagenSeleccionadaId = null;

        $this->dispatch('crearImagen')->to(\App\Livewire\Cliente\CrearOEditarImagen::class);
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
