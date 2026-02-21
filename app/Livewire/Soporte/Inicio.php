<?php

namespace App\Livewire\Soporte;
use Illuminate\Support\Facades\Auth;

use Livewire\Component;
use App\Models\Ticket;

class Inicio extends Component
{
    public $tickets_abiertos, $tickets_proceso , $tickets_cerrados, $id;

    public function mount(){
        $this->id = Auth::id();
        $this->tickets_abiertos = Ticket::where('id_estado', 1)->where('id_usuario_asignado', $this->id)->count();
        $this->tickets_proceso = Ticket::where('id_estado', 2)->where('id_usuario_asignado', $this->id)->count();
        $this->tickets_cerrados = Ticket::where('id_estado', 3)->where('id_usuario_asignado', $this->id)->count();
    }

    public function render()
    {
        return view('livewire.soporte.inicio');
    }
}
