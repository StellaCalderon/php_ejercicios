<?php 
if (isset($_POST['enviar'])){
    echo $_POST['usuario'] . "<br/>";
    echo $_POST['foto'] . "<br/>";
    echo $_POST['aficcion'] . "<br/>";

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
        echo "<input type='submit' name='enviar' value='Enviar' />";
    echo '</form></body></html>';
}
?>