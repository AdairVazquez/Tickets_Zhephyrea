<?php

namespace App\Livewire\Admin;

use App\Models\Empresa;
use App\Models\Rol;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Mail;

use function Termwind\render;

class Usuarios extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind'; // Opcional: para estilos Tailwind

    public $openCrear = false;
    public $openEdit = false;

    public $roles;
    public $postIdDel;
    public $name, $email, $password, $password_confirmation, $rol_id, $empresa_id = '', $empresas, $nueva_empresa;

    public $UserEditId = '';
    Public $user_edit = [
        'name' => '',
        'email' => '',
        'password' => '',
        'rol_id' => ''
    ];

    protected $listeners = ['deletePost'];

    public function mount(){
        $this->roles = Rol::all();
        $this->empresas = Empresa::all();
    }

    public function mostrarCrear(){
        $this->openCrear = true;
    }

    public function cerrarCrear(){
        $this->openCrear = false;
    }

    public function cerrarEdit(){
        $this->openEdit = false;
    }

    public function save()
    {
        // Definimos las reglas base
        $rules = [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'rol_id'   => 'required|exists:rol,id',
            'password' => 'required|confirmed|min:8',
        ];

        // Lógica condicional para la Empresa
        if ($this->empresa_id === 'otro') {
            // Si es "otro", validamos el campo de texto y NO el ID en la BD
            $rules['nueva_empresa'] = 'required|min:3|unique:empresas,nombre_empresa';
        } else {
            // Si no es "otro", validamos que el ID exista en la tabla empresas
            $rules['empresa_id'] = 'required|exists:empresas,id';
        }

        $this->validate($rules);

        // --- PROCESAMIENTO ---

        $final_empresa_id = $this->empresa_id;

        if ($this->empresa_id === 'otro') {
            $nueva = Empresa::create(['nombre_empresa' => $this->nueva_empresa]);
            $final_empresa_id = $nueva->id;
        }

        $data = [
            'nombre' => $this->name,
            'correo' => $this->email,
            'contraseña' => $this->password
        ];

        $destinatario = $this->email;

        Mail::send('email.usuarioRegistrado', $data, function ($message) use ($destinatario){
            $message->to($destinatario)
                ->subject('Tus credenciales para Tickets Zephyrea');
        });

        User::create([
            'name'       => $this->name,
            'email'      => $this->email,
            'password'   => bcrypt($this->password),
            'rol_id'     => $this->rol_id,
            'id_empresa' => $final_empresa_id, // Usamos el ID final
        ]);

        $this->cerrarCrear();
    }

    public function edit($user_id){
        $this->openEdit = true;

        $this->UserEditId = $user_id;

        $usuario = User::find($user_id);

        $this->user_edit['name']=$usuario->name;
        $this->user_edit['email']=$usuario->email;
        $this->user_edit['password']=$usuario->password;
        $this->user_edit['rol_id']=$usuario->rol_id;
        $this->user_edit['empresa_id']=$usuario->empresa_id;

    }

    public function update(){
        $usuario = User::find($this->UserEditId);
        $usuario->update([
         'name' => $this -> user_edit['name'],
         'email' => $this->user_edit['email'],
         'rol_id' => $this->user_edit['id_rol'],
         'empresa_id' => $this->user_edit['empresa_id']
        ]);

        $this->reset(['name','email','rol_id','empresa_id']);
        $this->openEdit = false;
        $this->gotoPage(1);



        $this->dispatch('usuarioActualizado');
    }

    public function confirmDelete($id)
    {
        $this->postIdDel = $id;
        // Dispara evento Livewire → capturado por JS
        $this->dispatch('show-delete-confirmation');
    }

    public function deletePost()
    {
        $user = User::find($this->postIdDel);
        if ($user) {
            $user->delete();
            $this->dispatch('usuarioEliminado');
        }
    }

    public function render()
    {
        return view('livewire.admin.usuarios', [
            // Cargamos todo aquí para que siempre esté disponible y actualizado
            'usuarios' => User::with(['rol', 'empresa'])->orderBy('id', 'desc')->paginate(20),
            'roles'    => Rol::all(),
            'empresas' => Empresa::all(),
        ]);
    }
}
