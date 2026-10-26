<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Crear tabla con array()</h1>
    <?php
        $frutas = array("manzana", "banana", "naranja", "pera", 8=> "kiwi");
    ?>

    <table border="1" cellpadding ="5" cellspacing="2">
        <tr aligin="center">
            <td>Elemento</td>
            <?php
                foreach($frutas as $fruta){
                    echo "<td>$fruta</td>";
                }
            ?>
         </tr>
         <tr align="center">
            <td>Posición</td>
            <?php
                foreach($frutas as $posicion => $fruta){
                    echo "<td>$posicion</td>";
                }
            ?>
         </tr>
</body>
</html>