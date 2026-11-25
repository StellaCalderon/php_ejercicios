<?php
require_once("funciones.php");

$mesConsultado = "";
$errorMes = "";
$mostrarResultados = false;
$listadoFacturacion = array();

if (isset($_POST["btnCalcular"])) {
    if (isset($_POST["mesConsultado"])) {
        $mesConsultado = trim($_POST["mesConsultado"]);
    }

    if ($mesConsultado === "") {
        $errorMes = "Introduce un mes (AAAA-MM).";
    } else {
        if (!preg_match("/^[0-9]{4}-[0-9]{2}$/", $mesConsultado)) {
            $errorMes = "Formato incorrecto (AAAA-MM).";
        } else {
            $partesMes = explode("-", $mesConsultado);
            if (checkdate((int)$partesMes[1], 1, (int)$partesMes[0]) === false) {
                $errorMes = "Mes no válido.";
            } else {
                $listadoFacturacion = calcularFacturacionMes($mesConsultado);
                $mostrarResultados = true;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Facturación Mensual</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <h1>Facturación Mensual por Profesor</h1>
    <p>
        <a href="gestionarClases.php">Inicio</a> |
        <a href="reservarClase.php">Reservar clase</a>
    </p>

    <form action="facturacionMes.php" method="post">
        <p>
            <label for="mesConsultado">Mes (AAAA-MM):</label>
            <input type="text" name="mesConsultado" id="mesConsultado" value="<?php echo $mesConsultado; ?>">
            <input type="submit" name="btnCalcular" value="Calcular">
            <?php if ($errorMes !== "") { ?>
                <span class="error"><?php echo $errorMes; ?></span>
            <?php } ?>
        </p>
    </form>

    <?php if ($mostrarResultados === true) { ?>
        <h2>Resultados del mes <?php echo $mesConsultado; ?></h2>
        <?php if (count($listadoFacturacion) === 0) { ?>
            <p>No hay clases realizadas en ese mes.</p>
        <?php } else { ?>
            <table>
                <tr>
                    <th>Profesor</th>
                    <th>Clases</th>
                    <th>Minutos</th>
                    <th>Importe total</th>
                </tr>
                <?php foreach ($listadoFacturacion as $filaProfesor) { ?>
                    <tr>
                        <td><?php echo $filaProfesor["id"] . " - " . $filaProfesor["profesor"]; ?></td>
                        <td><?php echo $filaProfesor["clases"]; ?></td>
                        <td><?php echo $filaProfesor["minutos"]; ?></td>
                        <td><?php echo $filaProfesor["importe"]; ?> €</td>
                    </tr>
                <?php } ?>
            </table>
        <?php } ?>
    <?php } ?>
</body>
</html>