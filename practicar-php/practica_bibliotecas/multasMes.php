<?php
require_once("funcionesBiblio.php");

$mesConsultado = "";
$errorMes = "";
$mostrarResultados = false;
$listadoMultas = array();

if (isset($_POST["btnCalcular"])) {
    $mesConsultado = isset($_POST["mesConsultado"]) ? trim($_POST["mesConsultado"]) : "";
    if ($mesConsultado === "" || !preg_match("/^[0-9]{4}-[0-9]{2}$/", $mesConsultado)) {
        $errorMes = "Formato incorrecto (AAAA-MM).";
    } else {
        $partes = explode("-", $mesConsultado);
        if (checkdate((int)$partes[1], 1, (int)$partes[0]) === false) {
            $errorMes = "Mes no válido.";
        } else {
            $listadoMultas = calcularMultasMes($mesConsultado);
            $mostrarResultados = true;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Multas Mensuales</title>
    <link rel="stylesheet" href="estilos.css">
</head>

<body>
    <h1>Penalizaciones por Retraso en un Mes</h1>
    <p><a href="biblioteca.php">Inicio</a> | <a href="nuevoPrestamo.php">Nuevo préstamo</a></p>

    <form action="multasMes.php" method="post">
        <label>Mes devolución (AAAA-MM):</label>
        <input type="text" name="mesConsultado" value="<?php echo $mesConsultado; ?>">
        <input type="submit" name="btnCalcular" value="Consultar">
        <span class="error"><?php echo $errorMes; ?></span>
    </form>

    <?php if ($mostrarResultados === true) { ?>
        <h2>Retrasos en <?php echo $mesConsultado; ?></h2>
        <?php if (count($listadoMultas) === 0) { ?>
            <p>No hubo devoluciones con retraso ese mes.</p>
        <?php } else { ?>
            <table>
                <tr>
                    <th>Socio</th>
                    <th>Libros con retraso</th>
                    <th>Días acumulados</th>
                    <th>Sanción total</th>
                </tr>
                <?php foreach ($listadoMultas as $fila) { ?>
                    <tr>
                        <td><?php echo $fila["socio"] . " - " . $fila["nombre"]; ?></td>
                        <td><?php echo $fila["librosRetraso"]; ?></td>
                        <td><?php echo $fila["diasTotales"]; ?></td>
                        <td><?php echo $fila["multaTotal"]; ?> €</td>
                    </tr>
                <?php } ?>
            </table>
        <?php } ?>
    <?php } ?>
</body>

</html>