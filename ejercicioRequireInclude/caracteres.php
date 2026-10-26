<?php
$comentario = "";
if (isset($_POST['comentario'])) {
    $comentario = $_POST['comentario'];     //  ' <script>alert("hack");</script> Juan ',
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ejemplo htmlspecialchars</title>
</head>
<body>
    <form method="post" action="">
        <label for="comentario">Escribe algo (prueba con &lt;script&gt;alert('Hola');&lt;/script&gt😉:</label><br>
        <textarea name="comentario" id="comentario"></textarea><br><br>
        <button type="submit">Enviar</button>
    </form>
    <h3>Resultado sin proteger (Inseguro):</h3>
    <div>
        <?php        // ¡PELIGRO! Si escriben un <script>, se ejecutará en el navegador
        echo $comentario; 
        ?>
    </div>
    <h3>Resultado protegido con htmlspecialchars (Seguro):</h3>
    <div>
        <?php           // Convierte <, >, &, " y ' en entidades HTML seguras
        echo htmlspecialchars($comentario, ENT_QUOTES, 'UTF-8'); 
        ?>
    </div></body></html> 