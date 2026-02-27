<?php

namespace App\Livewire\Soporte;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\Attributes\On;

class DetalleTicket extends Component
{
    public $ticketId;
    public $ticket, $ticketC;
    public $archivoC;
    public $postIdCerr;

    public function regresar(){
        if(Auth::user()->rol_id == 1 || Auth::user()->rol_id == 2){
            return redirect()->route('tickets');
        }else{
            return redirect()->route('MisTickets');
        }
    }

    public function confirmCerrar($id)
    {
        $this->postIdCerr = $id;
        $this->dispatch('show-cerrar-confirmation');
    }

    #[On('cerrarTicket')]
    public function cerrarTicket(){
        $ticket = Ticket::find($this->postIdCerr);
        if($ticket){
            $ticket->update([
            'id_estado' => 3,
            ]);
        }

        $destinatario = $ticket->creador->email;
        $data = ['titulo' => $ticket->titulo, 'nombre' => $ticket->asignado->name];

        Mail::send('email.ticketCerrado', $data, function ($message) use ($destinatario){
            $message->to($destinatario)
                ->subject('Conclusión del ticket');
        });

        $this->dispatch('ticketCerrado');
    }

    public function irChat($ticketId){
        return redirect()->route('chat', ['ticketId' => $ticketId]);
    }

    public function mount($ticketId)
    {
        $this->ticketId = $ticketId;
        $this->ticket = Ticket::with(['subcategoria', 'prioridad', 'estado', 'creador', 'asignado'])
        ->findOrFail($ticketId);
        $this->ticketC = Ticket::find($ticketId);
        $this->archivoC = $this->ticketC->archivo->ruta ?? '';
    }

    public function render()
    {
        return view('livewire.soporte.detalle-ticket');
    }
}
