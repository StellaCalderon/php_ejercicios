
<?php

//Solo contiene funciones que reciben el nombre del campo y su valor actual, 
//y te devuelven la etiqueta HTML ya montada (así cuando repintas un formulario con errores, 
//los datos que escribió el usuario no se borran)



// Pinta un <input type="text"> manteniendo el valor escrito al repintar
function crearText($nombre, $valor)
{
    $html = '<input type="text" name="' . $nombre . '" value="' . $valor . '">';
    return $html;
}

// Pinta un <input type="password">
function crearPassword($nombre)
{
    $html = '<input type="password" name="' . $nombre . '">';
    return $html;
}

// Pinta un <textarea> manteniendo el texto escrito al repintar
function crearTextarea($nombre, $valor, $filas = 4, $columnas = 40)
{
    $html = '<textarea name="' . $nombre . '" rows="' . $filas . '" cols="' . $columnas . '">' . $valor . '</textarea>';
    return $html;
}

// Pinta un <select> simple a partir de un array asociativo ('valor' => 'Texto visible')[cite: 203]
function crearSelectArray($nombreSelect, $opciones, $valorSeleccionado)
{
    $html = '<select name="' . $nombreSelect . '">';
    foreach ($opciones as $valor => $texto) {
        $selected = "";
        if ($valor == $valorSeleccionado) {
            $selected = "selected";
        }
        $html .= '<option value="' . $valor . '" ' . $selected . '>' . $texto . '</option>';
    }
    $html .= '</select>';
    return $html;
}

// Pinta botones radio a partir de un array asociativo ('Etiqueta' => 'valor')[cite: 203]
function crearRadio($nombreRadio, $opciones, $valorSeleccionado)
{
    $html = "";
    foreach ($opciones as $etiqueta => $valor) {
        $checked = "";
        if ($valor == $valorSeleccionado) {
            $checked = "checked";
        }
        $html .= '<label>' . $etiqueta . '</label>';
        $html .= '<input type="radio" name="' . $nombreRadio . '" value="' . $valor . '" ' . $checked . '> ';
    }
    return $html;
}

// Pinta checkboxes múltiples a partir de un array asociativo ('Etiqueta' => 'valor')[cite: 203, 255]
// OJO: $valoresSeleccionados tiene que ser un array (ej: $_POST['extras'])
function crearCheckboxMultiple($nombreCheckbox, $opciones, $valoresSeleccionados)
{
    $html = "";
    foreach ($opciones as $etiqueta => $valor) {
        $checked = "";
        if (is_array($valoresSeleccionados) && in_array($valor, $valoresSeleccionados)) {
            $checked = "checked";
        }
        $html .= '<label>' . $etiqueta . '</label>';
        $html .= '<input type="checkbox" name="' . $nombreCheckbox . '[]" value="' . $valor . '" ' . $checked . '> ';
    }
    return $html;
}

?>