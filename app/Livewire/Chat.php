<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Chat extends Component
{
    public $ticketId;
    public $id_usuario, $nombre_usuario, $rol;

    public function mount(){
        $this->id_usuario = Auth::id();
        $this->nombre_usuario = Auth::user()->name;
        $nombre_rol = User::find($this->id_usuario);
        $this->rol = $nombre_rol->rol->nombre_rol;
        $this->ticketId = request()->query('ticketId');
    }

    public function render()
    {
        return view('livewire.chat');
    }
}
