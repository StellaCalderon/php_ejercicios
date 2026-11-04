<?php
$usuario = '';
$foto = '';
$errores = array(false, false);
$enviado = isset($_POST['enviar']);

if ($enviado) {
    // Sanitizamos los datos recibidos
    $usuario = trim($_POST['usuario'] ?? '');
    $foto = trim($_POST['foto'] ?? '');

    // Validación de campos
    if (empty($usuario)) $errores[0] = true;
    if (empty($foto))    $errores[1] = true;
}

// Si se envió y NO hay ningún error (false en el array)
if ($enviado && !in_array(true, $errores, true)) {
    echo 'Datos recogidos correctamente<br>';
    echo htmlspecialchars($usuario, ENT_QUOTES, 'UTF-8') . '<br>';
    echo htmlspecialchars($foto, ENT_QUOTES, 'UTF-8') . '<br>';
} else {
    // Se muestra el formulario (bien por primera vez, o repintado con errores)
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inserción de fotografía</title>
</head>
<body>
    Inserción de la fotografía del usuario:
    <form action="repintaFormularioSencillo.php" method="post">
        <label>Nombre usuario:</label>
        <input type="text" name="usuario" value="<?php echo htmlspecialchars($usuario, ENT_QUOTES, 'UTF-8'); ?>"/>
        <?php if ($errores[0]): ?>
            <span style="color:red; font-size:12px;">Usuario inválido</span>
        <?php endif; ?>
        <br/>

        <label>Nombre de su fotografía:</label>
        <input type="text" name="foto" value="<?php echo htmlspecialchars($foto, ENT_QUOTES, 'UTF-8'); ?>"/>
        <?php if ($errores[1]): ?>
            <span style="color:red; font-size:12px;">Foto inválida</span>
        <?php endif; ?>
        <br/>

        <input type="submit" name="enviar" value="Enviar">
    </form>
</body>
</html>
<?php
}
?>