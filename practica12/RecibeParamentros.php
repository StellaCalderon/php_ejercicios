<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Recibe Parametros</title>
</head>
<body>
<?php
date_default_timezone_set('Europe/Madrid');

$errores = array();

if (empty($_POST['nombre'])) {
    $errores[] = "El nombre no puede estar vacio";
}

if (strlen($_POST['clave']) < 6 || strlen($_POST['clave']) > 12) {
    $errores[] = "La clave tiene que tener entre 6 y 12 caracteres";
}

if (!isset($_POST['genero'])) {
    $errores[] = "Hay que seleccionar un genero";
}

if (empty($_POST['fechaNacimiento'])) {
    $errores[] = "La fecha de nacimiento es obligatoria";
} else {
    $fecha = DateTime::createFromFormat('d/m/y', $_POST['fechaNacimiento']);
    if ($fecha == false) {
        $errores[] = "La fecha tiene que tener formato dd/mm/aa";
    } else {
        $hoy = new DateTime();
        if ($fecha > $hoy) {
            $fecha->modify('-100 years');
        }
        $intervalo = $hoy->diff($fecha);
        if ($intervalo->y < 18) {
            $errores[] = "Tienes que ser mayor de 18 años";
        }
    }
}

if (!isset($_POST['pais'])) {
    $errores[] = "Tienes que seleccionar al menos un pais";
}

if (!isset($_POST['acepto'])) {
    $errores[] = "Tienes que marcar acepto";
} else {
    if ($_POST['acepto'] != 'OK') {
        $errores[] = "El valor de acepto no es correcto";
    }
}

if ($_FILES['foto']['name'] != "") {
    $nombreArchivo = $_FILES['foto']['name'];
    $extension = strtolower(substr(strrchr($nombreArchivo, '.'), 1));
    
    if ($extension != 'jpg' && $extension != 'png' && $extension != 'gif') {
        $errores[] = "La foto debe ser jpg, png o gif";
    }
    
    if ($_FILES['foto']['size'] > 5242880) {
        $errores[] = "La foto no puede ocupar mas de 5MB";
    }
}

if (count($errores) > 0) {
    for ($i = 0; $i < count($errores); $i++) {
        echo "<p style='color:red;'>" . $errores[$i] . "</p>";
    }
} else {
    echo "Nombre: " . $_POST['nombre'] . "<br />";
    echo "Clave: " . $_POST['clave'] . "<br />";
    echo "Genero: " . $_POST['genero'] . "<br />";
    echo "Fecha de nacimiento: " . $_POST['fechaNacimiento'] . "<br />";
    echo "Pais: ";
    foreach ($_POST['pais'] as $pais) {
        echo $pais . " ";
    }
    echo "<br />";
    echo "Acepto: " . $_POST['acepto'] . "<br />";
    echo "Comentarios: " . $_POST['comentarios'] . "<br />";
    if ($_FILES['foto']['name'] != "") {
        echo "Foto: " . $_FILES['foto']['name'] . "<br />";
    }
}
?>
</body>
</html>