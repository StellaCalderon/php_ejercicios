<?php
require_once("funciones.php");

$pasoFormulario = "paso1";
$codigoAlumno = "";
$asignaturaElegida = "";
$codigoProfesor = "";
$fechaClase = "";
$horaClase = "";
$duracionMinutos = "";

$mensajeExito = "";
$errorFichero = "";
$errorAlumno = "";
$errorAsignatura = "";
$errorProfesor = "";
$errorFecha = "";
$errorHora = "";
$errorDuracion = "";
$errorDisponibilidad = "";

if (isset($_POST["btnPaso1"])) {
    if (isset($_POST["codigoAlumno"])) {
        $codigoAlumno = trim($_POST["codigoAlumno"]);
    }
    if (isset($_POST["AsignaturaElegida"])) {
        $asignaturaElegida = trim($_POST["AsignaturaElegida"]);
    }

    if ($codigoAlumno === "") {
        $errorAlumno = "Selecciona un alumno.";
    }

    if ($asignaturaElegida === "") {
        $errorAsignatura = "Selecciona una asignatura.";
    }

    if ($errorAlumno === "" && $errorAsignatura === "") {
        $pasoFormulario = "paso2";
    }
}

if (isset($_POST["btnGuardar"])) {
    if (isset($_POST["codigoAlumno"])) {
        $codigoAlumno = trim($_POST["codigoAlumno"]);
    }
    if (isset($_POST["AsignaturaElegida"])) {
        $asignaturaElegida = trim($_POST["AsignaturaElegida"]);
    }
    if (isset($_POST["codigoProfesor"])) {
        $codigoProfesor = trim($_POST["codigoProfesor"]);
    }
    if (isset($_POST["fechaClase"])) {
        $fechaClase = trim($_POST["fechaClase"]);
    }
    if (isset($_POST["horaClase"])) {
        $horaClase = trim($_POST["horaClase"]);
    }
    if (isset($_POST["duracionMinutos"])) {
        $duracionMinutos = trim($_POST["duracionMinutos"]);
    }

    $pasoFormulario = "paso2";

    if ($codigoProfesor === "") {
        $errorProfesor = "Selecciona un profesor.";
    }

    if ($fechaClase === "") {
        $errorFecha = "La fecha es obligatoria (AAAA-MM-DD).";
    } else {
        if (comprobarFormatoFecha($fechaClase) === false) {
            $errorFecha = "Fecha no válida (AAAA-MM-DD).";
        }
    }

    if ($horaClase === "") {
        $errorHora = "La hora es obligatoria (HH:MM).";
    } else {
        if (comprobarFormatoHora($horaClase) === false) {
            $errorHora = "Hora no válida (HH:MM).";
        }
    }

    if ($duracionMinutos === "" || !is_numeric($duracionMinutos)) {
        $errorDuracion = "La duración debe ser un número.";
    } else {
        $duracionNumero = (int)$duracionMinutos;
        if ($duracionNumero < 30 || $duracionNumero > 120) {
            $errorDuracion = "La duración debe estar entre 30 y 120 minutos.";
        }
    }

    if ($errorProfesor === "" && $errorFecha === "" && $errorHora === "" && $errorDuracion === "") {
        if (comprobarDisponibilidadProfesor($codigoProfesor, $fechaClase, $horaClase, (int)$duracionMinutos) === false) {
            $errorDisponibilidad = "El profesor no tiene disponibilidad en ese día y franja horaria.";
        }
    }

    if (
        $errorAlumno === "" && $errorAsignatura === "" && $errorProfesor === "" && $errorFecha === "" && $errorHora === "" &&
        $errorDuracion === "" && $errorDisponibilidad === ""
    ) {
        $partesHora = explode(":", $horaClase);
        $horaFormateada = str_pad((string)(int)$partesHora[0], 2, "0", STR_PAD_LEFT) . ":" . str_pad((string)(int)$partesHora[1], 2, "0", STR_PAD_LEFT);

        $nuevaReserva = $codigoProfesor . ";" . $codigoAlumno . ";" . $asignaturaElegida . ";" . $fechaClase . ";" . $horaFormateada . ";" . (int)$duracionMinutos . ";PENDIENTE" . PHP_EOL;

        $archivoClases = fopen("clases.txt", "a");
        if ($archivoClases !== false) {
            fputs($archivoClases, $nuevaReserva);
            fclose($archivoClases);

            $mensajeExito = "Reserva guardada correctamente con estado PENDIENTE.";
            $pasoFormulario = "paso1";
            $codigoAlumno = "";
            $asignaturaElegida = "";
            $codigoProfesor = "";
            $fechaClase = "";
            $horaClase = "";
            $duracionMinutos = "";
        } else {
            $errorFichero = "No se ha podido abrir clases.txt.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reservar Clase</title>
    <link rel="stylesheet" href="estilos.css">
</head>

<body>
    <h1>Reservar Clase: </h1>
    <p>
        <a href="gestionarClases.php">Inicio</a> |
        <a href="facturacionMes.php">Facturación mensual</a>
    </p>

    <?php if ($mensajeExito !== "") { ?>
        <p class="ok"><?php echo $mensajeExito; ?></p>
    <?php } ?>

    <?php if ($errorFichero !== "") { ?>
        <p class="error"><?php echo $errorFichero; ?></p>
    <?php } ?>

    <?php if ($pasoFormulario === "paso1") { ?>
        <h2>Alumno y asignatura</h2>
        <form action="reservarClase.php" method="post">
            <p>
                <label for="codigoAlumno">Alumno:</label>
                <select name="codigoAlumno" id="codigoAlumno">
                    <option value=""></option>
                    <?php
                    $archivoAlumnos = fopen("alumnos.txt", "r");
                    if ($archivoAlumnos !== false) {
                        $lineaAlumno = fgets($archivoAlumnos);
                        while ($lineaAlumno !== false) {
                            $lineaLimpia = trim($lineaAlumno);
                            if ($lineaLimpia !== "") {
                                $datosAlumno = explode(";", $lineaLimpia);
                                $idAlumno = trim($datosAlumno[0]);
                                $nombreAlumno = trim($datosAlumno[1]) . " " . trim($datosAlumno[2]);
                                $seleccionado = "";
                                if (strcmp($codigoAlumno, $idAlumno) === 0) {
                                    $seleccionado = " selected";
                                }
                                echo '<option value="' . $idAlumno . '"' . $seleccionado . '>' . $idAlumno . ' - ' . $nombreAlumno . '</option>';
                            }
                            $lineaAlumno = fgets($archivoAlumnos);
                        }
                        fclose($archivoAlumnos);
                    }
                    ?>
                </select>
                <?php if ($errorAlumno !== "") { ?>
                    <span class="error"><?php echo $errorAlumno; ?></span>
                <?php } ?>
            </p>

            <p>
                <label for="asignaturaElegida">Asignatura:</label>
                <select name="asignaturaElegida" id="asignaturaElegida">
                    <option value=""></option>
                    <?php
                    $listaAsignaturas = obtenerAsignaturas();
                    foreach ($listaAsignaturas as $asignatura) {
                        $seleccionada = "";
                        if (strcmp($asignaturaElegida, $asignatura) === 0) {
                            $seleccionada = " selected";
                        }
                        echo '<option value="' . $asignatura . '"' . $seleccionada . '>' . $asignatura . '</option>';
                    }
                    ?>
                </select>
                <?php if ($errorAsignatura !== "") { ?>
                    <span class="error"><?php echo $errorAsignatura; ?></span>
                <?php } ?>
            </p>

            <p>
                <input type="submit" name="btnPaso1" value="Continuar">
            </p>
        </form>
    <?php } else { ?>
        <h2>Paso 2: Profesor, fecha, hora y duración</h2>
        <form action="reservarClase.php" method="post">
            <input type="hidden" name="codigoAlumno" value="<?php echo $codigoAlumno; ?>">
            <input type="hidden" name="asignaturaElegida" value="<?php echo $asignaturaElegida; ?>">

            <p>Alumno: <strong><?php echo $codigoAlumno; ?></strong> | Asignatura: <strong><?php echo $asignaturaElegida; ?></strong></p>

            <p>
                <label for="codigoProfesor">Profesor:</label>
                <select name="codigoProfesor" id="codigoProfesor">
                    <option value="">Elige profesor</option>
                    <?php
                    $listaProfesores = obtenerProfesoresPorAsignatura($asignaturaElegida);
                    foreach ($listaProfesores as $profesor) {
                        $seleccionado = "";
                        if (strcmp($codigoProfesor, $profesor["id"]) === 0) {
                            $seleccionado = " selected";
                        }
                        echo '<option value="' . $profesor["id"] . '"' . $seleccionado . '>' . $profesor["nombre"] . ' ' . $profesor["apellidos"] . '</option>';
                    }
                    ?>
                </select>
                <?php if ($errorProfesor !== "") { ?>
                    <span class="error"><?php echo $errorProfesor; ?></span>
                <?php } ?>
            </p>

            <p>
                <label for="fechaClase">Fecha (AAAA-MM-DD):</label>
                <input type="text" name="fechaClase" id="fechaClase" value="<?php echo $fechaClase; ?>">
                <?php if ($errorFecha !== "") { ?>
                    <span class="error"><?php echo $errorFecha; ?></span>
                <?php } ?>
            </p>

            <p>
                <label for="horaClase">Hora (HH:MM):</label>
                <input type="text" name="horaClase" id="horaClase" value="<?php echo $horaClase; ?>">
                <?php if ($errorHora !== "") { ?>
                    <span class="error"><?php echo $errorHora; ?></span>
                <?php } ?>
            </p>

            <p>
                <label for="duracionMinutos">Duración (30-120 min):</label>
                <input type="text" name="duracionMinutos" id="duracionMinutos" value="<?php echo $duracionMinutos; ?>">
                <?php if ($errorDuracion !== "") { ?>
                    <span class="error"><?php echo $errorDuracion; ?></span>
                <?php } ?>
            </p>

            <?php if ($errorDisponibilidad !== "") { ?>
                <p class="error"><?php echo $errorDisponibilidad; ?></p>
            <?php } ?>

            <p>
                <input type="submit" name="btnGuardar" value="Guardar reserva">
                <a href="reservarClase.php">Volver</a>
            </p>
        </form>
    <?php } ?>
</body>

</html>