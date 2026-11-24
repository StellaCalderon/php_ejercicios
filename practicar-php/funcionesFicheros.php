<?php

// 1. Pinta un <select> sacando una columna concreta de un fichero (" " o ";")
// $columna es la posición tras hacer explode: 0 = primera columna, 1 = segunda, etc.
// Evita opciones duplicadas automáticamente
function crearSelectFichero($nombreSelect, $nombreFichero, $separador, $columna, $valorSeleccionado) {
    $html = '<select name="' . $nombreSelect . '">';
    $html .= '<option value="">-- Selecciona --</option>';
    $yaMetidos = array();

    if (file_exists($nombreFichero)) {
        $fichero = @fopen($nombreFichero, "r");
        if ($fichero) {
            while (!feof($fichero)) {
                $linea = trim(fgets($fichero));
                if ($linea != "") {
                    $datos = explode($separador, $linea);
                    if (isset($datos[$columna])) {
                        $valor = trim($datos[$columna]);
                        if (!in_array($valor, $yaMetidos)) {
                            $yaMetidos[] = $valor;
                            $selected = "";
                            if ($valor == $valorSeleccionado) {
                                $selected = "selected";
                            }
                            $html .= '<option value="' . $valor . '" ' . $selected . '>' . $valor . '</option>';
                        }
                    }
                }
            }
            fclose($fichero);
        }
    }

    $html .= '</select>';
    return $html;
}
// Lee cualquier fichero de texto y devuelve un array con todas sus filas ya troceadas
function leerFichero($nombreFichero, $separador) {
    $filas = array();

    if (file_exists($nombreFichero)) {
        $fichero = @fopen($nombreFichero, "r");
        if ($fichero) {
            while (!feof($fichero)) {
                $linea = trim(fgets($fichero));
                if ($linea != "") {
                    $filas[] = explode($separador, $linea);
                }
            }
            fclose($fichero);
        }
    }

    return $filas;
}

// 2. Comprueba si un valor existe en una columna concreta de un fichero (devuelve true o false)
function existeEnFichero($nombreFichero, $separador, $columna, $valorBuscado) {
    $encontrado = false;

    if (file_exists($nombreFichero)) {
        $fichero = @fopen($nombreFichero, "r");
        if ($fichero) {
            while (!feof($fichero) && !$encontrado) {
                $linea = trim(fgets($fichero));
                if ($linea != "") {
                    $datos = explode($separador, $linea);
                    if (isset($datos[$columna]) && trim($datos[$columna]) == $valorBuscado) {
                        $encontrado = true;
                    }
                }
            }
            fclose($fichero);
        }
    }

    return $encontrado;
}

// 3. Añade una línea nueva al final de un fichero de texto (modo 'a', sin borrar lo anterior)
function guardarLineaFichero($nombreFichero, $lineaTexto) {
    $fichero = @fopen($nombreFichero, "a");
    if (!$fichero) {
        return false;
    }
    fwrite($fichero, $lineaTexto . "\r\n");
    fclose($fichero);
    return true;
}

?>