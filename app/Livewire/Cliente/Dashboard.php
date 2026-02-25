<?php

namespace App\Livewire\Cliente;

use Livewire\Component;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public $tickets_abiertos, $tickets_proceso , $tickets_cerrados, $id;

    public function mount(){
        $this->id = Auth::id();
        $this->tickets_abiertos = Ticket::where('id_estado', 1)->where('id_usuario_creador', $this->id)->count();
        $this->tickets_proceso = Ticket::where('id_estado', 2)->where('id_usuario_creador', $this->id)->count();
        $this->tickets_cerrados = Ticket::where('id_estado', 3)->where('id_usuario_creador', $this->id)->count();
    }

    public function render()
    {
        return view('livewire.cliente.dashboard');
    }
}
