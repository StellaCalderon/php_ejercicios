<?php
require_once 'Funciones.php';
date_default_timezone_set('Europe/Madrid');

$errores = array();
$datos = array();
$correcto = false;

$arrayGenero = array('Mujer' => 'F', 'Hombre' => 'M');
$arrayPaises = array('España' => 'ES', 'Francia' => 'FR', 'Portugal' => 'PT', 'Italia' => 'IT');
$arrayAcepto = array('acepto' => array('etiqueta' => 'Acepto:', 'valor' => 'OK'));

if (isset($_POST['enviar'])) {
    
    if (empty($_POST['nombre'])) {
        $errores['nombre'] = "El nombre no puede estar vacio";
    }


    if (strlen($_POST['clave']) < 6 || strlen($_POST['clave']) > 12) {
        $errores['clave'] = "La clave debe tener entre 6 y 12 carácteres";
    }

    if (!isset($_POST['genero'])) {
        $errores['genero'] = "Debe seleccionar un género";
    }

    if (empty($_POST['fechaNacimiento'])) {
        $errores['fechaNacimiento'] = "La fecha es obligatoria";
    } else {
        $fecha = DateTime::createFromFormat('d/m/y', $_POST['fechaNacimiento']);
        if ($fecha == false) {
            $errores['fechaNacimiento'] = "Formato incorrecto (dd/mm/aa)";
        } else {
            $hoy = new DateTime();
            if ($fecha > $hoy) {
                $fecha->modify('-100 years');
            }
            $intervalo = $hoy->diff($fecha);
            if ($intervalo->y < 18) {
                $errores['fechaNacimiento'] = "Debe ser mayor de 18 años";
            }
        }
    }

    if (!isset($_POST['pais'])) {
        $errores['pais'] = "Debe seleccionar un país";
    }

    if (!isset($_POST['acepto'])) {
        $errores['acepto'] = "Debe aceptar las condiciones";
    } else {
        if ($_POST['acepto'] != 'OK') {
            $errores['acepto'] = "Valor incorrecto";
        }
    }

    if ($_FILES['foto']['name'] != "") {
        $extension = strtolower(substr(strrchr($_FILES['foto']['name'], '.'), 1));
        if ($extension != 'jpg' && $extension != 'png' && $extension != 'gif') {
            $errores['foto'] = "Solo jpg, png o gif";
        }
        if ($_FILES['foto']['size'] > 5242880) {
            $errores['foto'] = "Maximo 5MB";
        }
    }

    if (count($errores) == 0) {
        $correcto = true;
        $datos = array();
    } else {
        $datos = $_POST;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Recibe y Repinta</title>
</head>
<body>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
        <input type="text" name="nombre" value="<?php if (isset($datos['nombre'])) { echo $datos['nombre']; } ?>" />
        <?php
        if (isset($errores['nombre'])) {
            echo "<span style='color:red;'> " . $errores['nombre'] . "</span>";
        }
        ?>
        <br>

        <input type="password" name="clave" value="<?php if (isset($datos['clave'])) { echo $datos['clave']; } ?>" />
        <?php
        if (isset($errores['clave'])) {
            echo "<span style='color:red;'> " . $errores['clave'] . "</span>";
        }
        ?>
        <br>

        <?php
        if (isset($datos['genero'])) {
            $gen = $datos['genero'];
        } else {
            $gen = '';
        }
        echo generaBotonesRadio('genero', $arrayGenero, $gen);

        if (isset($errores['genero'])) {
            echo "<span style='color:red;'> " . $errores['genero'] . "</span>";
        }
        ?>
        <br>

        <input type="text" name="fechaNacimiento" value="<?php if (isset($datos['fechaNacimiento'])) { echo $datos['fechaNacimiento']; } ?>" />
        <?php
        if (isset($errores['fechaNacimiento'])) {
            echo "<span style='color:red;'> " . $errores['fechaNacimiento'] . "</span>";
        }
        ?>
        <br>

        <?php
        if (isset($datos['pais'])) {
            $paises = $datos['pais'];
        } else {
            $paises = array();
        }
        echo generaSelectMultiple('pais', $arrayPaises, $paises);

        if (isset($errores['pais'])) {
            echo "<span style='color:red;'> " . $errores['pais'] . "</span>";
        }
        ?>
        <br>

        <?php
        echo generaCajasVerificacion($arrayAcepto, $datos);

        if (isset($errores['acepto'])) {
            echo "<span style='color:red;'> " . $errores['acepto'] . "</span>";
        }
        ?>
        <br>

       
        <textarea name="comentarios" rows="4" cols="40">
            <?php 
                if (isset($datos['comentarios'])) { echo $datos['comentarios']; }
            ?>
        </textarea>
        <br>

        <input type="file" name="foto" />
        <?php
        if (isset($errores['foto'])) {
            echo "<span style='color:red;'> " . $errores['foto'] . "</span>";
        }
        ?>
        <br>

        <input type="submit" name="enviar" value="Enviar" />
    </form>

    <?php
    if ($correcto == true) {
        echo "Nombre: " . $_POST['nombre'] . "<br>";
        echo "Clave: " . $_POST['clave'] . "<br>";
        echo "Genero: " . $_POST['genero'] . "<br>";
        echo "Fecha de nacimiento: " . $_POST['fechaNacimiento'] . "<br>";
        echo "Pais: ";

        foreach ($_POST['pais'] as $pais) {
            echo $pais . " ";
        }
        echo "<br>";

        echo "Acepto: " . $_POST['acepto'] . "<br>";
        echo "Comentarios: " . $_POST['comentarios'] . "<br>";
        if ($_FILES['foto']['name'] != "") {
            echo "Foto: " . $_FILES['foto']['name'] . "<br>";
        }
    }
    ?>
</body>
</html>