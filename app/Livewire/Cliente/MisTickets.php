<?php

namespace App\Livewire\Cliente;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MisTickets extends Component
{
    public $usuariosA;
    public $id_usuarioA = [];
    public $Ticket_id;


    public function asignarUsuario($ticketId)
    {
        $ticket = Ticket::find($ticketId);
        $ticket->update([
            'id_usuario_asignado' => $this->id_usuarioA[$ticketId] ?? null,
            'id_estado' => 2
        ]);

        $this->dispatch('ticketCerrado');
    }

    public function irADetalles($ticketId)
    {
        return redirect()->route('detalleTicket', ['ticketId' => $ticketId]);
    }

    public function mount()
    {
        $this->usuariosA = User::whereIn('rol_id', [1, 2])->get();
    }

    public function render()
    {
        $usuarioCreador = Auth::id();
        return view('livewire.cliente.mis-tickets', [
            'ticketsAbiertos' => Ticket::where('id_estado', 1)
                ->where('id_usuario_creador', $usuarioCreador)
                ->orderBy('id', 'desc')
                ->paginate(10, ['*'], 'abiertosPage'), // <--- Nombre único para el parámetro de URL

            'ticketsProceso' => Ticket::where('id_estado', 2)
                ->where('id_usuario_creador', $usuarioCreador)
                ->orderBy('id', 'desc')
                ->paginate(10, ['*'], 'procesoPage'),

            'ticketsCerrados' => Ticket::where('id_estado', 3)
                ->where('id_usuario_creador', $usuarioCreador)
                ->orderBy('id', 'desc')
                ->paginate(10, ['*'], 'cerradosPage'),
        ]);
    }
}
