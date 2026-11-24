<?php

// Valida y sube un archivo a una carpeta destino.
// Devuelve el nombre final con el que se guardó, o false si falla algo.
function subirArchivo($nombreInputFile, $carpetaDestino, $extensionesPermitidas, $tamanoMaxBytes) {
    if (!isset($_FILES[$nombreInputFile]) || !is_uploaded_file($_FILES[$nombreInputFile]['tmp_name'])) {
        return false;
    }

    $nombreOriginal = $_FILES[$nombreInputFile]['name'];
    $tamano = $_FILES[$nombreInputFile]['size'];
    $tmp = $_FILES[$nombreInputFile]['tmp_name'];

    // Sacamos la extensión en minúsculas (ej: "foto.JPG" -> "jpg")
    $trozos = explode(".", $nombreOriginal);
    $extension = strtolower(end($trozos));

    // Comprobamos extensión permitida
    if (!in_array($extension, $extensionesPermitidas)) {
        return false;
    }

    // Comprobamos tamaño máximo (ej. 5MB = 5 * 1024 * 1024)
    if ($tamano > $tamanoMaxBytes) {
        return false;
    }

    // Movemos a la carpeta destino con nombre único usando time()
    if (is_dir($carpetaDestino)) {
        $nombreFinal = time() . "-" . $nombreOriginal;
        if (move_uploaded_file($tmp, $carpetaDestino . "/" . $nombreFinal)) {
            return $nombreFinal;
        }
    }

    return false;
}

?>