<?php 
if (isset($_POST['enviar'])){
    echo $_POST['usuario'] . "<br/>";
    echo $_POST['foto'] . "<br/>";
    echo $_POST['aficcion'] . "<br/>";
    echo $_POST['correo'] . "<br/>";
    echo $_POST['clave'] . "<br/>";
    echo $_POST['color'] . "<br/>";
    echo "Extras seleccionados:<br/>";
    if (isset($_POST['extras'])) {
        foreach ($_POST['extras'] as $extra) {
            echo "- " . $extra . "<br/>";
        }
    }
}
else {
    echo '<html><body> 
    <form action="" method="post">';

        echo "Nombre usuario: <input type='text' name='usuario' /><br/>";
        echo "Nombre de su fotografía: <input type='text' name='foto' /><br/>";

        echo "Sexo: <br/>";
        echo "<input type='radio' name='sexo' value='M' checked>Mujer ";
        echo "<input type='radio' name='sexo' value='H'>Hombre<br/>";

        echo "Afición favorita: <br/>";
        echo "<input type='radio' name='aficcion' value='deporte'>Deporte<br/>";
        echo "<input type='radio' name='aficcion' value='musica'>Música <br/>";
        echo "<input type='hidden' name='correo' value='stella@domenico.es'/>";
        echo "<input type='password' name='clave' /><br/>";

        echo " <select name='color'>";
        echo "<option value='rojo'>Rojo</option>";
        echo "<option value='verde'>Verde</option>";
        echo "<option value='azul'>Azul</option>";
        echo "</select>";

        echo "<input type='checkbox' name='extras[]' value='garaje'>Garaje ";
        echo "<input type='checkbox' name='extras[]' value='piscina'>Piscina ";
        echo "<input type='checkbox' name='extras[]' value='jardin'>Jardín ";

        echo "<input type='submit' name='enviar' value='Enviar' />";

    echo '</form></body></html>';
}
?>