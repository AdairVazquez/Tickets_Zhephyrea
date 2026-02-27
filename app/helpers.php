<?php

// Forzamos el namespace para que Livewire encuentre la función
namespace Livewire\Features\SupportFileUploads;

if (!function_exists('Livewire\Features\SupportFileUploads\tmpfile')) {
    function tmpfile() {
        // Creamos un archivo real en tu carpeta de storage en lugar de la del sistema
        $path = storage_path('app/livewire-tmp/' . uniqid('livewire_tmp_', true));
        touch($path);
        return fopen($path, 'r+');
    }
}

