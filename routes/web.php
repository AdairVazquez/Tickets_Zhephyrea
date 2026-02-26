<?php

use App\Livewire\Chat;
use App\Livewire\Soporte\DetalleTicket;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;


Route::get('/preview-mail', function () {
    // Simulamos un ticket para la prueb

    // Retornamos la vista del correo pasando los datos
    return view('email.nuevoTicket');
});


Route::get('/send-test-mail', function () {
    $data = [];
    try {

        Mail::send('email.nuevoTicket', $data ,function($message) {
            $message->to('chrisaban08@gmail.com')
                    ->subject('Nuevo Ticket Registrado');
        });
        return "Correo enviado con éxito. Revisa tu bandeja de entrada.";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

Route::redirect('/', '/login')->name('home');

Route::get('/chat', function () {
    return view('cliente.chat');
})->name('chat');


// RUTAS ADMIN
Route::view('dashboardAdministrador', 'admin.dashboard')
    ->middleware(['auth', 'verified', 'rol.admin'])
    ->name('admin.dashboard');

Route::view('usuarios', 'admin.usuarios')
    ->middleware(['auth', 'verified', 'rol.admin'])
    ->name('usuarios');

//RUTAS DE TICKETS

Route::view('tickets', 'admin.tickets')
    ->middleware(['auth', 'verified'])
    ->name('tickets');

Route::view('nuevoTicket', 'cliente.nuevoTicket')
    ->middleware(['auth', 'verified'])
    ->name('nuevoTicket');

Route::get('/detalleTicket/{ticketId}', DetalleTicket::class)
    ->middleware(['auth', 'verified'])
    ->name('detalleTicket');

Route::get('/chatTicket/{ticketId}', Chat::class)
    ->middleware(['auth', 'verified'])
    ->name('chat');

// RUTAS DE IMAGENES

Route::view('imagenes', 'admin.imagenes')
    ->middleware(['auth', 'verified'])
    ->name('imagenes');

Route::view('nuevaImagen', 'cliente.nuevaImagen')
    ->middleware(['auth', 'verified'])
    ->name('nuevaImagen');

//RUTAS SOPORTE
Route::view('dashboardSoporte', 'soporte.dashboard')
    ->middleware(['auth', 'verified'])
    ->name('soporte.dashboard');

//RUTAS CLIENTE

Route::view('MisTickets', 'cliente.misTickets')
    ->middleware(['auth', 'verified'])
    ->name('MisTickets');

Route::view('dashboardCliente', 'cliente.dashboard')
    ->middleware(['auth', 'verified'])
    ->name('cliente.dashboard');



Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

require __DIR__.'/auth.php';
