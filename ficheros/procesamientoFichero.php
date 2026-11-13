<?php 
$fichero = fopen("alumnos.txt", "r");
if (!$fichero) {
    die("Error al abrir el fichero.");
}

$cursos_agrupados = array();

while(!feof($fichero)) {
    $linea = fgets($fichero);
    
    // Saltarse líneas vacías si las hay al final del archivo
    if (trim($linea) == "") {
        continue;
    }

    // Separamos usando el guion largo original "–"
    $partes = explode("–", trim($linea));
    
    // Validamos que realmente se haya dividido en 2 partes
    if (count($partes) == 2) {
        $nombre = $partes[0];
        $curso = $partes[1];
        
        // Agrupamos directamente aquí
        $cursos_agrupados[$curso][] = $nombre;
    }
}

fclose($fichero);

echo "<pre>";
var_dump($cursos_agrupados);
echo "</pre>";

$fichero = fopen("alumnos.txt", "a");
fputs($fichero,`Hola, nueva línea`);
fclose($fichero);
?>
