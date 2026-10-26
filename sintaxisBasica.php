<?php
echo "<p>Párrafo 1", "Aaaaaaaaa </p>";
echo "<p>Párrafo 2</p>\n";

echo "<p>Párrafo 1\n", "Aaaaaaaaa </p>";
echo "<p>Párrafo 2</p>";
$cadena = <<<xxx
Primera línea
Segunda línea
Tercera línea<br>
xxx;
echo $cadena;
echo "<IMG src='https://www.w3schools.com/images/w3schools_green.jpg'>";  



$varN=1;
$varC='2 flores';
$varC=$varC+$varN; 
echo "<p>$varC</p>";

$varN=(double)1;
$varC='4 flores';
$varC=$varN+ $varC; 
echo "<p>$varC</p>";

$varN=1;
$varC='4 flores';
$varC=$varN.$varC; 
echo "<p>$varC</p>";

$varN=1;
$varC='flores 2';
$varC=$varC+$varN; 
echo "<p>$varC</p>";

?>