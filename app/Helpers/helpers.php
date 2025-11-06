<?php

// Arquivo de helpers globais do sistema.
// Pode adicionar funções utilitárias aqui.

if (!function_exists('app_name')) {
    function app_name(): string
    {
        return config('app.name', 'FlowToDo');
    }
}
