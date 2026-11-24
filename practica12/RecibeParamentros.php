<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Recibe Parámetros</title>
</head>

<body>
    <?php

    $errores = array();

    if (empty($_POST['nombre'])) {
        $errores[] = "El nombre no puede estar vacío";
    }

    if (strlen($_POST['clave']) < 6 || strlen($_POST['clave']) > 12) {
        $errores[] = "La clave tiene que tener entre 6 y 12 carácteres";
    }

    if (!isset($_POST['sexo'])) {
        $errores[] = "Hay que seleccionar un sexo";
    }

    if (empty($_POST['fechaNacimiento'])) {
        $errores[] = "La fecha de nacimiento es obligatoria";
    } else {
        $fecha = $_POST['fechaNacimiento'];
        $anio = substr($fecha, -2); 
        
        if ($anio <= date('y')) {
            $anio = "20" . $anio;
        } else {
            $anio = "19" . $anio;
        }

        $hoy = date('Y');
        if (($hoy - $anio) < 18) {
            $errores[] = "Debe ser mayor de 18 años";
        }
    }
    $comentarios= $_POST['comentarios'];
    if (!isset($_POST['pais'])) {
        $errores[] = "Tienes que seleccionar al menos un país";
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
        echo "Nombre: " . $_POST['nombre'] . "<br>";
        echo "Clave: " . $_POST['clave'] . "<br>";
        echo "Sexo: " . $_POST['sexo'] . "<br>";
        echo "Fecha de nacimiento: " . $_POST['fechaNacimiento'] . "<br>";
        echo "Pais: ";
        foreach ($_POST['pais'] as $pais) {
            echo $pais . " ";
        }
        echo "<br>";
        echo "Acepto: " . $_POST['acepto'] . "<br>";
        echo "Comentarios: ".$_POST['comentarios']. "<br>";
        if ($_FILES['foto']['name'] != "") {
            echo "Foto: " . $_FILES['foto']['name'] . "<br>";
        }
    }

?>
</body>

</html>