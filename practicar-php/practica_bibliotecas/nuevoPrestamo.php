<?php
require_once("funcionesBiblio.php");

$pasoFormulario = "paso1";
$numSocio = "";
$categoriaElegida = "";
$isbnLibro = "";
$fechaSalida = "";
$diasPrestamo = "";

$mensajeExito = "";
$errorSocio = "";
$errorCategoria = "";
$errorLibro = "";
$errorFecha = "";
$errorDias = "";
$errorValidacion = "";

if (isset($_POST["btnPaso1"])) {
    $numSocio = isset($_POST["numSocio"]) ? trim($_POST["numSocio"]) : "";
    $categoriaElegida = isset($_POST["categoriaElegida"]) ? trim($_POST["categoriaElegida"]) : "";

    if ($numSocio === "") {
        $errorSocio = "Elige un socio.";
    }
    if ($categoriaElegida === "") {
        $errorCategoria = "Elige una categoría.";
    }
    if ($errorSocio === "" && $errorCategoria === "") {
        $pasoFormulario = "paso2";
    }
}

if (isset($_POST["btnGuardar"])) {
    $numSocio = trim($_POST["numSocio"]);
    $categoriaElegida = trim($_POST["categoriaElegida"]);
    $isbnLibro = trim($_POST["isbnLibro"]);
    $fechaSalida = trim($_POST["fechaSalida"]);
    $diasPrestamo = trim($_POST["diasPrestamo"]);
    $pasoFormulario = "paso2";

    if ($isbnLibro === "") {
        $errorLibro = "Selecciona un libro disponible.";
    }
    if ($fechaSalida === "" || comprobarFormatoFecha($fechaSalida) === false) {
        $errorFecha = "Fecha no válida (AAAA-MM-DD).";
    }
    if ($diasPrestamo === "" || !is_numeric($diasPrestamo) || (int)$diasPrestamo < 7 || (int)$diasPrestamo > 30) {
        $errorDias = "El préstamo debe ser entre 7 y 30 días.";
    }

    if ($errorLibro === "" && $errorFecha === "" && $errorDias === "") {
        if (comprobarSocioYFecha($numSocio, $fechaSalida) === false) {
            $errorValidacion = "El socio está sancionado o la biblioteca cierra en domingo.";
        }
    }

    if ($errorLibro === "" && $errorFecha === "" && $errorDias === "" && $errorValidacion === "") {
        $fechaTope = date("Y-m-d", strtotime($fechaSalida . " + " . (int)$diasPrestamo . " days"));
        $idNuevo = rand(10, 999);
        $lineaNueva = $idNuevo . ";" . $numSocio . ";" . $isbnLibro . ";" . $fechaSalida . ";" . $fechaTope . ";;PRESTADO" . PHP_EOL;

        $f = fopen("prestamos.txt", "a");
        if ($f !== false) {
            fputs($f, $lineaNueva);
            fclose($f);
            $mensajeExito = "Préstamo registrado. Fecha límite de devolución: " . $fechaTope;
            $pasoFormulario = "paso1";
            $numSocio = "";
            $categoriaElegida = "";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Préstamo</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <h1>Registrar Préstamo</h1>
    <p><a href="biblioteca.php">Inicio</a> | <a href="multasMes.php">Informe de multas</a></p>

    <?php if ($mensajeExito !== "") { echo '<p class="ok">' . $mensajeExito . '</p>'; } ?>

    <?php if ($pasoFormulario === "paso1") { ?>
        <form action="nuevoPrestamo.php" method="post">
            <p>
                <label>Socio:</label>
                <select name="numSocio">
                    <option value=""></option>
                    <?php
                    $f = fopen("socios.txt", "r");
                    if ($f !== false) {
                        $linea = fgets($f);
                        while ($linea !== false) {
                            $limpia = trim($linea);
                            if ($limpia !== "") {
                                $datos = explode(";", $limpia);
                                $sel = (strcmp($numSocio, trim($datos[0])) === 0) ? " selected" : "";
                                echo '<option value="' . trim($datos[0]) . '"' . $sel . '>' . trim($datos[0]) . ' - ' . trim($datos[1]) . ' ' . trim($datos[2]) . '</option>';
                            }
                            $linea = fgets($f);
                        }
                        fclose($f);
                    }
                    ?>
                </select>
                <span class="error"><?php echo $errorSocio; ?></span>
            </p>
            <p>
                <label>Categoría:</label>
                <select name="categoriaElegida">
                    <option value=""></option>
                    <?php
                    foreach (obtenerCategorias() as $cat) {
                        $sel = (strcmp($categoriaElegida, $cat) === 0) ? " selected" : "";
                        echo '<option value="' . $cat . '"' . $sel . '>' . $cat . '</option>';
                    }
                    ?>
                </select>
                <span class="error"><?php echo $errorCategoria; ?></span>
            </p>
            <input type="submit" name="btnPaso1" value="Siguiente">
        </form>
    <?php } else { ?>
        <form action="nuevoPrestamo.php" method="post">
            <input type="hidden" name="numSocio" value="<?php echo $numSocio; ?>">
            <input type="hidden" name="categoriaElegida" value="<?php echo $categoriaElegida; ?>">
            <p>Socio: <strong><?php echo $numSocio; ?></strong> | Categoría: <strong><?php echo $categoriaElegida; ?></strong></p>
            <p>
                <label>Libro disponible:</label>
                <select name="isbnLibro">
                    <option value="">Selecciona libro</option>
                    <?php
                    foreach (obtenerLibrosPorCategoria($categoriaElegida) as $libro) {
                        $sel = (strcmp($isbnLibro, $libro["isbn"]) === 0) ? " selected" : "";
                        echo '<option value="' . $libro["isbn"] . '"' . $sel . '>' . $libro["titulo"] . ' (' . $libro["autor"] . ')</option>';
                    }
                    ?>
                </select>
                <span class="error"><?php echo $errorLibro; ?></span>
            </p>
            <p><label>Fecha salida (AAAA-MM-DD):</label> <input type="text" name="fechaSalida" value="<?php echo $fechaSalida; ?>"> <span class="error"><?php echo $errorFecha; ?></span></p>
            <p><label>Días préstamo (7-30):</label> <input type="text" name="diasPrestamo" value="<?php echo $diasPrestamo; ?>"> <span class="error"><?php echo $errorDias; ?></span></p>
            <p class="error"><?php echo $errorValidacion; ?></p>
            <input type="submit" name="btnGuardar" value="Confirmar préstamo">
            <a href="nuevoPrestamo.php">Volver</a>
        </form>
    <?php } ?>
</body>
</html>