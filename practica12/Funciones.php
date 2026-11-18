<?php

function generaBotonesRadio($nombre, $opciones, $seleccionado = '') {
    $cadena= "";
    foreach ($opciones as $etiqueta => $valor) {
        if ($valor == $seleccionado) {
            $cadena = $cadena . "<label>" . $etiqueta . "</label><input type=\"radio\" name=\"" . $nombre . "\" value=\"" . $valor . "\" checked=\"checked\" />\n";
        } else {
            $cadena = $cadena . "<label>" . $etiqueta . "</label><input type=\"radio\" name=\"" . $nombre . "\" value=\"" . $valor . "\" />\n";
        }
    }
    return $cadena;
}

function generaCajasVerificacion($checkboxes, $enviados = array()) {
    $cadena = "";
    foreach ($checkboxes as $nombre => $datos) {
        $etiqueta = $datos['etiqueta'];
        $valor = $datos['valor'];

        if (isset($enviados[$nombre])) {
            if ($enviados[$nombre] == $valor) {
                $cadena = $cadena . "<label>" . $etiqueta . "</label><input type=\"checkbox\" name=\"" . $nombre . "\" value=\"" . $valor . "\" checked=\"checked\" />\n";
            } else {
                $cadena = $cadena . "<label>" . $etiqueta . "</label><input type=\"checkbox\" name=\"" . $nombre . "\" value=\"" . $valor . "\" />\n";
            }
        } else {
            $cadena = $cadena . "<label>" . $etiqueta . "</label><input type=\"checkbox\" name=\"" . $nombre . "\" value=\"" . $valor . "\" />\n";
        }
    }
    return $cadena;
}

function generaSelectSimple($nombre, $opciones, $seleccionado = '') {
    $cadena = "<select name='$nombre'>\n";
    foreach ($opciones as $etiqueta => $valor) {
        if ($valor == $seleccionado) {
            $cadena = $cadena . "<option value=\"" . $valor . "\" selected=\"selected\">" . $etiqueta . "</option>\n";
        } else {
            $cadena = $cadena . "<option value=\"" . $valor . "\">" . $etiqueta . "</option>\n";
        }
    }
    $cadena = $cadena . "</select>\n";
    return $cadena;
}

function generaSelectMultiple($nombre, $opciones, $seleccionados = array()) {
    $cadena = "<select name=\"" . $nombre . "[]\" multiple=\"multiple\">\n";
    foreach ($opciones as $etiqueta => $valor) {
        if (in_array($valor, $seleccionados)) {
            $cadena = $cadena . "<option value=\"" . $valor . "\" selected=\"selected\">" . $etiqueta . "</option>\n";
        } else {
            $cadena = $cadena . "<option value=\"" . $valor . "\">" . $etiqueta . "</option>\n";
        }
    }
    $cadena = $cadena . "</select>\n";
    return $cadena;
}

?>