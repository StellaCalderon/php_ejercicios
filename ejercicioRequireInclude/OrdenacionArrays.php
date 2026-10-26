<?php 

$arrayNumeros = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
$arrrayFrutas = array("manzana", "banana", "naranja", "uva", "kiwi");

echo "<strong>Valores iniciales:</strong><br>";
echo "<pre>";
print_r($arrayNumeros);
print_r($arrrayFrutas);
echo "</pre>";

echo "<h2>Ordenación de arrays</h2>";

// 1. sort() - Ordena de menor a mayor (modifica el array por referencia y devuelve bool)
sort($arrayNumeros);
sort($arrrayFrutas);
echo "<strong>sort() - Ascendente:</strong><br>";
echo "<pre>";
print_r($arrayNumeros);
print_r($arrrayFrutas);
echo "</pre>"; // Corregido: Faltaba esta etiqueta

// 2. krsort() - Ordena por clave en orden descendente
$arrayNumeros = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
$arrrayFrutas = array("manzana", "banana", "naranja", "uva", "kiwi");
krsort($arrayNumeros);
krsort($arrrayFrutas);
echo "<strong>krsort() - Por clave descendente:</strong><br>";
echo "<pre>";
print_r($arrayNumeros);
print_r($arrrayFrutas);
echo "</pre>";

// 3. usort() - Ordena usando una función de comparación personalizada
function comparar($a, $b) {
    if ($a == $b) {
        return 0;
    }
    return ($a < $b) ? -1 : 1;
}

$arrayNumeros = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
$arrrayFrutas = array("manzana", "banana", "naranja", "uva", "kiwi");
usort($arrayNumeros, "comparar");
usort($arrrayFrutas, "comparar");
echo "<strong>usort() - Personalizado:</strong><br>";
echo "<pre>";
print_r($arrayNumeros);
print_r($arrrayFrutas);
echo "</pre>";

// 4. shuffle() - Mezcla de forma aleatoria (Corregido: era 'suffle')
shuffle($arrayNumeros);
shuffle($arrrayFrutas);
echo "<strong>shuffle() - Aleatorio:</strong><br>";
echo "<pre>";
print_r($arrayNumeros);
print_r($arrrayFrutas);
echo "</pre>";

// 5. rsort() - Ordena de mayor a menor
$arrayNumeros = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
$arrrayFrutas = array("manzana", "banana", "naranja", "uva", "kiwi");
rsort($arrayNumeros);
rsort($arrrayFrutas);
echo "<strong>rsort() - Descendente:</strong><br>";
echo "<pre>";
print_r($arrayNumeros);
print_r($arrrayFrutas);
echo "</pre>";  

// 6. asort() - Ordena manteniendo la asociación de índices
$arrayNumeros = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
$arrrayFrutas = array("manzana", "banana", "naranja", "uva", "kiwi");
asort($arrayNumeros);
asort($arrrayFrutas);
echo "<strong>asort() - Mantiene índices:</strong><br>";
echo "<pre>";
print_r($arrayNumeros);
print_r($arrrayFrutas); 
echo "</pre>";

// 7. array_multisort() - Ordena múltiples arrays o arrays multidimensionales
$arrayNumeros = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10); // Corregido: era 'r2'
$arrrayFrutas = array("manzana", "banana", "naranja", "uva", "kiwi");

// Se ejecuta la función y luego se imprimen los arrays modificados
array_multisort($arrayNumeros, SORT_DESC, $arrrayFrutas);
echo "<strong>array_multisort() - Multidimensional/Múltiple:</strong><br>";
echo "<pre>";
print_r($arrayNumeros);
print_r($arrrayFrutas);
echo "</pre>";

?>