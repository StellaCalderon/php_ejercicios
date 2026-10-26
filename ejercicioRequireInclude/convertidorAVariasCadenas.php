<?php
$cadena="Hola a todos beibis";
$cadena2="Hola a todos beibis";
$tok=strtok($cadena, " ");
$tok2=strtok($cadena2, " ");

$contador= 0;
while ($tok !== false ) {
$contador++;
    echo $tok . "<br>";
    echo $contador . " ";
    $tok = strtok(" ");
 
}

$tok2=strtok($cadena2, " ");

while ($tok2 !== false) {
$contador++;
    echo $tok2 . "<br>";
    echo $contador . " ";
    $tok2 = strtok(" ");
}



$arrayString = str_split($cadena, 1);
echo "<br>";
foreach ($arrayString as $letra) {
    echo $letra . "<br>";
}
?>