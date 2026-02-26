<?php

namespace App\Livewire\Cliente;

use App\Models\Archivo;
use App\Models\Prioridad;
use App\Models\Subcategoria;
use App\Models\Ticket;
use App\Models\User;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class NuevoTicket extends Component
{
    use WithFileUploads;
    public $categorias, $prioridades;
    public $titulo, $descripcion, $id_subcategoria='', $id_prioridad = '', $archivo;
    public $id_estado = 1;
    public $id_usuario_creador;


    public function save()
    {
        $this->validate([
            'titulo' => 'required',
            'descripcion' => 'required',

        ],[
            'name.required'=> 'Campo olbigatorio, escribe un nombre'
        ]);
        $this->id_usuario_creador = Auth::id();


         // 1️⃣ Guardar el archivo físico (si se sube)
        $archivoId = null;
        if ($this->archivo) {
            $ruta = $this->archivo->store('adjuntos', 'public');

            // Crear registro en tabla archivos
            $archivo = Archivo::create([
                'ruta' => $ruta,
                'tipo' => $this->archivo->getMimeType(),
            ]);

            $archivoId = $archivo->id;
        }

        // 2️⃣ Crear el ticket y vincular el id del archivo
        Ticket::create([
            'titulo' => $this->titulo,
            'descripcion' => $this->descripcion,
            'id_usuario_creador' => Auth::id(),
            'id_subcategoria' => $this->id_subcategoria ?? null,
            'id_prioridad' => $this->id_prioridad ?? null,
            'id_estado' => $this->id_estado ?? 1,
            'id_archivo' => $archivoId,
        ]);

        $destinatarios = User::where('rol_id', '!=', 3)->pluck('email')->toArray();
        $data = [];
        
        Mail::send('email.nuevoTicket', $data, function ($message) use ($destinatarios){
            $message->to($destinatarios)
                ->subject('Nuevo ticket registrado');
        });

        $this->reset(['titulo', 'descripcion', 'archivo']);
        session()->flash('mensaje', 'Ticket creado correctamente');
        $this->dispatch('ticketCreado');

    }

    public function mount(){
        $this->categorias = Subcategoria::all();
        $this->prioridades = Prioridad::all();
    }

    public function render()
    {
        return view('livewire.cliente.nuevo-ticket');
    }
}
