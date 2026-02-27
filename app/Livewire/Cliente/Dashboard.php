<?php

namespace App\Livewire\Cliente;

use Livewire\Component;
use App\Models\Imagen;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public $tickets_abiertos, $tickets_proceso , $tickets_cerrados, $id, $imagenes;

    public function mount(){
        $this->id = Auth::id();
        $this->imagenes = Imagen::where('estado_id', 1)
            ->orderBy('fecha_subida', 'desc')
            ->get();
        $this->tickets_abiertos = Ticket::where('id_estado', 1)->where('id_usuario_creador', $this->id)->count();
        $this->tickets_proceso = Ticket::where('id_estado', 2)->where('id_usuario_creador', $this->id)->count();
        $this->tickets_cerrados = Ticket::where('id_estado', 3)->where('id_usuario_creador', $this->id)->count();
    }

    public function render()
    {
        return view('livewire.cliente.dashboard');
    }
}
