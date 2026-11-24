<?php
//strtotime convierte una fecha en texto (como "2016-12-23") a un número entero
function crearText($nombre, $valor)
{
    return '<input type="text" name="' . $nombre . '" value="' . $valor . '">';
}

function rangoFechasValido($fechaInicio, $fechaFin)
{
    $f1 = DateTime::createFromFormat('d/m/y', $fechaInicio);
    $f2 = DateTime::createFromFormat('d/m/y', $fechaFin);
    // --- SI EL FORMATO FUESE NORMAL (AAAA-MM-DD, ej: 2016-12-23 o input type="date") ---
    // $f1 = new DateTime($fechaInicio);
    // $f2 = new DateTime($fechaFin);
    return ($f1 <= $f2);
}

$matricula = "";
$lugar = "";
$fechaInicio = "";
$fechaFin = "";
// 4 posibles errores: [0] matricula, [1] lugar, [2] fechaInicio, [3] fechaFin
$errores = array(false, false, false, false);
$mensaje = "";

if (isset($_POST['solicitar'])) {
    //strtoupper Convierte toda la cadena a MAYÚSCULAS
    $matricula = strtoupper(trim($_POST['matricula']));
    $lugar = trim($_POST['lugar']);
    $fechaInicio = trim($_POST['fechaInicio']);
    $fechaFin = trim($_POST['fechaFin']);

    // 1. Validar matrícula (4 números, guion y 3 letras mayúsculas)
    if (empty($matricula)) {
        $errores[0] = "Debes rellenar la matrícula.";
    } elseif (!preg_match('/^[0-9]{4}-[A-Z]{3}$/', $matricula)) {
        $errores[0] = "Formato incorrecto (usa 1234-BCD).";
    }

    // 2. Validar dirección u hotel
    if (empty($lugar)) {
        $errores[1] = "Debes indicar la dirección o el hotel.";
    }

    // 3. Validar fecha inicial
    if (empty($fechaInicio)) {
        $errores[2] = "Debes rellenar la fecha inicial.";
    } elseif (!DateTime::createFromFormat('d/m/y', $fechaInicio)) {
        $errores[2] = "Formato incorrecto (usa dd/mm/aa).";
    }
    // --- SI EL FORMATO FUESE NORMAL (AAAA-MM-DD) ---
    // } elseif (!strtotime($fechaInicio)) {
    //     $errores[2] = "Fecha incorrecta (usa AAAA-MM-DD).";
    // }

    // 4. Validar fecha final y que no sea anterior a la inicial
    if (empty($fechaFin)) {
        $errores[3] = "Debes rellenar la fecha final.";
    } elseif (!DateTime::createFromFormat('d/m/y', $fechaFin)) {
        $errores[3] = "Formato incorrecto (usa dd/mm/aa).";
    } elseif ($errores[2] == false && !rangoFechasValido($fechaInicio, $fechaFin)) {
        $errores[3] = "La fecha inicial debe ser anterior o igual a la final.";
    }
    // --- SI EL FORMATO FUESE NORMAL (AAAA-MM-DD) ---
    // } elseif (!strtotime($fechaFin)) {
    //     $errores[3] = "Fecha incorrecta (usa AAAA-MM-DD).";
    // } elseif ($errores[2] == false && !rangoFechasValido($fechaInicio, $fechaFin)) {
    //     $errores[3] = "La fecha inicial debe ser anterior o igual a la final.";
    // }

    // Si NO hay ningún error, escribimos en txt/residentesYHoteles.txt
    if ($errores[0] == false && $errores[1] == false && $errores[2] == false && $errores[3] == false) {
        // Quitamos espacios del lugar sustituyéndolos por barra baja (_) para respetar el formato del txt
        // si no lo quito al usuario poner "Hotel Rigoberta" me va a separar en otro trozo más
        // porque tendrá un " " entre medias
        $lugarFormateado = str_replace(" ", "_", $lugar);

        // Pasamos las fechas de dd/mm/aa a AAAA/MM/DD (que es como van en residentesYHoteles.txt)
        $f1 = DateTime::createFromFormat('d/m/y', $fechaInicio)->format('Y/m/d');
        $f2 = DateTime::createFromFormat('d/m/y', $fechaFin)->format('Y/m/d');
        // --- SI EL FORMATO FUESE NORMAL (AAAA-MM-DD) Y SE GUARDARA TAL CUAL ---
        // $f1 = (new DateTime($fechaInicio))->format('Y-m-d');
        // $f2 = (new DateTime($fechaFin))->format('Y-m-d');


        // Montamos la línea con salto de línea al final (PHP_EOL)
        // Ponemos PHP_EOL delante para asegurar que baja de renglón aunque el fichero original no acabara en Enter
        $nuevaLinea = "\n" . $matricula . " " . $lugarFormateado . " " . $f1 . " " . $f2;
        // Abrimos en modo "a" (append = añadir al final sin borrar lo que ya había)
        $fichero = fopen("txt/residentesYHoteles.txt", "a");
        if ($fichero) {
            fwrite($fichero, $nuevaLinea);
            fclose($fichero);
            $mensaje = "Permiso guardado correctamente para la matrícula $matricula.";
            // Limpiamos los campos tras guardar
            $matricula = "";
            $lugar = "";
            $fechaInicio = "";
            $fechaFin = "";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Solicitar Permiso</title>
    <link rel="stylesheet" href="estilos.css">
</head>

<body>
    <h3>Solicitar Permiso Residente / Hotel</h3>
    <form action="solicitarPermiso.php" method="post">
        <p>
            Matrícula (1234-BCD): <?php echo crearText("matricula", $matricula); ?>
            <?php if ($errores[0] != false) echo '<span class="error">' . $errores[0] . '</span>'; ?>
        </p>
        <p>
            Dirección / Hotel: <?php echo crearText("lugar", $lugar); ?>
            <?php if ($errores[1] != false) echo '<span class="error">' . $errores[1] . '</span>'; ?>
        </p>
        <p>
            Fecha Inicio (dd/mm/aa): <?php echo crearText("fechaInicio", $fechaInicio); ?>
            <?php if ($errores[2] != false) echo '<span class="error">' . $errores[2] . '</span>'; ?>
        </p>
        <p>
            Fecha Fin (dd/mm/aa): <?php echo crearText("fechaFin", $fechaFin); ?>
            <?php if ($errores[3] != false) echo '<span class="error">' . $errores[3] . '</span>'; ?>
        </p>
        <p><input type="submit" name="solicitar" value="Guardar Permiso"></p>
    </form>

    <?php if ($mensaje != "") echo "<p><strong>$mensaje</strong></p>"; ?>
    <p><a href="listadoInfractores.php">Ir a Listado de Infractores</a></p>
</body>

</html>