<?php
require("cadena.php");
require("fecha.php");

include("cabecera.html");
include("pie.html");



$frutas= array("manzana", "pera", "naranja", "platano");
foreach($frutas as $fruta){
    echo $fruta."<br>";
    echo "La fruta es: ".$fruta." número: ".array_search($fruta, $frutas)."<br>";
}
$nuevasFrutas= $frutas;
sort($nuevasFrutas);
echo "<br>Frutas ordenadas:<br>";
foreach($nuevasFrutas as $fruta){
    echo $fruta."<br>";
}
foreach($frutas as $clave => $valor){
    echo "Clave: ".$clave." Valor: ".$valor."<br>";
}



?>