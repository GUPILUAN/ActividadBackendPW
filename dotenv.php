<?php

function cargarVariablesEnv($archivo)
{
    if (!file_exists($archivo)) {
        throw new Exception("El archivo $archivo no existe.");
    }

    $lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lineas as $linea) {

        if (strpos(trim($linea), '#') === 0) {
            continue;
        }
    
        list($variable, $valor) = explode('=', $linea, 2);
        
        
        putenv("$variable=$valor");
        $_ENV[$variable] = $valor;
        $_SERVER[$variable] = $valor;
    }
}

$dotenv = cargarVariablesEnv(__DIR__ . '/.env');

?>