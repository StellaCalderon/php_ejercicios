
<?php 
if (isset($_POST ['enviar'])){
    echo $_POST['usuario'];
    echo $_POST['foto'];
} else {
    echo '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procesar Cadena</title>
</head>
<body>

    <form action ="" method="post" enctype="multipart/form-data">';

echo 'Nombre usuario: <input type= "text" name ="usuario"/><br/>';
echo 'Nombre de su fotografía <input type="text" name="foto"/><br/>';

echo '<input type="submit" name= "enviar" value="Enviar">
</form>
</body>
</html>';
}
?>