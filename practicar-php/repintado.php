<?php
require("funcionesFormulario.php");
require("funcionesFicheros.php");

// 1. Variables iniciales vacías para la primera carga
$nombre = "";
$clave = "";
$genero = "";
$ciudad = "";
$extras = array();
$comentario = "";

// Array de errores (uno por cada campo obligatorio)
$errores = array(false, false, false, false);

// 2. Comprobamos si se ha pulsado el botón Enviar
if (isset($_POST['enviar'])) {
    $nombre = trim($_POST['nombre']);
    $clave = trim($_POST['clave']);
    $ciudad = $_POST['ciudad'];
    $comentario = trim($_POST['comentario']);

    if (isset($_POST['genero'])) {
        $genero = $_POST['genero'];
    }
    if (isset($_POST['extras'])) {
        $extras = $_POST['extras'];
    }

    // 3. Validaciones
    if (empty($nombre)) {
        $errores[0] = true;
    }
    if (strlen($clave) < 6 || strlen($clave) > 12) {
        $errores[1] = true;
    }
    if (empty($genero)) {
        $errores[2] = true;
    }
    if (empty($ciudad)) {
        $errores[3] = true;
    }

    // 4. Si NO hay errores, mostramos los datos recogidos
    if (!in_array(true, $errores)) {
        echo "<html><head><link rel='stylesheet' type='text/css' href='estilos.css'></head><body>";
        echo "<h3>Formulario correcto:</h3>";
        echo "Nombre: " . $nombre . "<br>";
        echo "Género: " . $genero . "<br>";
        echo "Ciudad: " . $ciudad . "<br>";
        echo "Comentario: " . $comentario . "<br>";
        echo "<br><a href='repintado.php'>Volver</a>";
        echo "</body></html>";
        exit;
    }
}
?>
<html>

<head>
    <title>Formulario DWES</title>
    <link rel="stylesheet" type="text/css" href="estilos.css">
</head>

<body>
    <h3>Formulario con validación y repintado</h3>
    <form action="repintado.php" method="post">

        Nombre: <?php echo crearText("nombre", $nombre); ?>
        <?php if ($errores[0]) echo '<span class="error">Nombre obligatorio</span>'; ?>
        <br><br>

        Clave (6-12 caracteres): <?php echo crearPassword("clave"); ?>
        <?php if ($errores[1]) echo '<span class="error">Entre 6 y 12 caracteres</span>'; ?>
        <br><br>

        Género:
        <?php
        $generos = array("Mujer" => "M", "Hombre" => "H");
        echo crearRadio("genero", $generos, $genero);
        ?>
        <?php if ($errores[2]) echo '<span class="error">Selecciona género</span>'; ?>
        <br><br>

        Ciudad (desde fichero):
        <?php echo crearSelectFichero("ciudad", "vehiculos.txt", " ", 2, $ciudad); ?>
        <?php if ($errores[3]) echo '<span class="error">Selecciona ciudad</span>'; ?>
        <br><br>

        Extras:
        <?php
        $listaExtras = array("Garaje" => "garaje", "Piscina" => "piscina");
        echo crearCheckboxMultiple("extras", $listaExtras, $extras);
        ?>
        <br><br>

        Comentario:<br>
        <?php echo crearTextarea("comentario", $comentario); ?>
        <br><br>

        <input type="submit" name="enviar" value="Enviar">
    </form>
</body>

</html>