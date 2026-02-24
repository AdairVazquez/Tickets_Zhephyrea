<?php

namespace App\Livewire\Admin;

use App\Models\Imagen;
use App\Models\User;
use Livewire\Component;

class Imagenes extends Component
{
    public function render()
    {
        return view('livewire.admin.imagenes', [
            'imagenes' => Imagen::orderBy('id', 'desc')->paginate(10),
        ]);
    }
}
