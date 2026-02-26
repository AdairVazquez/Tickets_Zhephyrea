<?php

namespace App\Livewire\Admin;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Tickets extends Component
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

        $usuario = User::find($this->id_usuarioA)->first();

        $nombre = $usuario->name;
        $titulo = $ticket->titulo;

        $data = [
            'nombre' => $nombre,
            'titulo' => $titulo
        ];

        $destinatario = $ticket->creador->email;

        Mail::send('email.actualizacionTicket', $data, function ($message) use ($destinatario){
            $message->to($destinatario)
                ->subject('Actualización en tu ticket');
        });


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
        return view('livewire.admin.tickets', [
            'ticketsAbiertos' => Ticket::where('id_estado', 1)->orderBy('id', 'desc')->paginate(10),
            'ticketsProceso' => Ticket::where('id_estado', 2)->orderBy('id', 'desc')->paginate(10),
            'ticketsCerrados' => Ticket::where('id_estado', 3)->orderBy('id', 'desc')->paginate(10),
        ]);
    }
}
