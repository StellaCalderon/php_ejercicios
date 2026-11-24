<?php

// 1. Comprueba si una fecha (formato 'YYYY-MM-DD' o 'YYYY/MM/DD') corresponde a mayor de 18 años
function esMayorDeEdad($fechaNacimiento) {
    $nacimiento = new DateTime($fechaNacimiento);
    $hoy = new DateTime("now");
    $diferencia = $nacimiento->diff($hoy);
    
    if ($diferencia->y >= 18) {
        return true;
    } else {
        return false;
    }
}

// 2. Comprueba si $fechaInicio es anterior o igual a $fechaFin (formato 'YYYY/MM/DD')
function rangoFechasValido($fechaInicio, $fechaFin) {
    $f1 = new DateTime($fechaInicio);
    $f2 = new DateTime($fechaFin);
    
    if ($f1 <= $f2) {
        return true;
    } else {
        return false;
    }
}
// Comprueba si dos fechas en formato DD/MM/AA son válidas y $fechaInicio <= $fechaFin
function rangoFechasValidoFormato($fechaInicio, $fechaFin) {
    //poner Y mayúscula si quiero que formato sea "DD/MM/AAAA"
    $f1 = DateTime::createFromFormat('d/m/y', $fechaInicio);
    $f2 = DateTime::createFromFormat('d/m/y', $fechaFin);

    // Si el usuario escribió mal el formato (ej: "hola" o "99/99/99"), devuelve false
    if (!$f1 || !$f2) {
        return false;
    }

    if ($f1 <= $f2) {
        return true;
    } else {
        return false;
    }
}
// 3. Valida un correo electrónico
function validarEmail($email) {
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}

// 4. Recorta un texto a un máximo de caracteres (ej: comentario máximo 150 caracteres)
function recortarTexto($texto, $maxCaracteres) {
    if (strlen($texto) > $maxCaracteres) {
        return substr($texto, 0, $maxCaracteres);
    }
    return $texto;
}

?>