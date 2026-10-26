<?php 
// 1. DECLARACIÓN DE VARIABLES CON LOS TEXTOS
$textoFaro = "El viejo faro de la costa llevaba años apagado, pero esa noche una luz tenue comenzó a parpadear en lo alto de la torre. Clara, que caminaba por la orilla con su perro, se detuvo en seco. Tras dudar unos segundos, empujó la pesada puerta de madera y subió los escalones de piedra. Al llegar arriba, no encontró a nadie; sobre la mesa de trabajo solo parpadeaba una antigua linterna de aceite junto a una carta abierta que decía: \"El camino se ilumina solo para quien se atreve a buscarlo\".";

$textoOtono = "El otoño llegó sin hacer ruido, pintando el bosque con pinceladas de oro, cobre y ámbar. Las hojas secas, desprendidas por la brisa, bailaban en el aire como pequeñas mariposas doradas antes de descansar sobre la tierra húmeda. Al fondo, el murmullo del río arrastraba los secretos de la montaña, mientras los primeros rayos del sol de la mañana atravesaban la niebla, entibiando el suelo y llenando todo el paisaje de una paz profunda y luminosa.";

$saludoOriginal = "Hola a todos los chicos";


// 2. MOSTRAR TEXTOS Y BUSCAR POSICIONES
echo "<strong>Texto del faro:</strong><br>" . $textoFaro . "<br><br>";

// strpos busca en qué posición (número de carácter) empieza la palabra "llevaba"
$posicionPalabra = strpos($textoFaro, "llevaba");
echo "<strong>Posición de la palabra 'llevaba':</strong> " . $posicionPalabra . "<br><br>";


// 3. MODIFICACIÓN DE TEXTO (Reemplazar "todos" por "todas")
echo "<strong>Saludo original:</strong><br>" . $saludoOriginal . "<br><br>";

// substr_replace(cadena, reemplazo, desde_dónde_empieza, cuántos_caracteres_quita)
// "todos" empieza en la posición 7 y mide 5 caracteres de largo
$saludoModificado = substr_replace($saludoOriginal, "todas", 7, 5);

echo "<strong>Saludo modificado:</strong><br>" . $saludoModificado;



// Devuelve una cadena resultado de sustituir todas las apariciones de una cadena por otra
$saludoReemplazado = str_replace("chicos", "chicas", $saludoOriginal);
echo "<br><strong>Saludo reemplazado:</strong><br>" . $saludoReemplazado;


//sustituye caracter a caracter que aparece en la cadena
$saludoCaracter = strtr($saludoOriginal, "so","Xa");
echo "<br><strong>Saludo caracter a caracter:</strong><br>" . $saludoCaracter;

//devuelve el número de apariciones dentoro de la cadena
$saludoContar = substr_count($saludoOriginal, "o");
echo "<br><strong>Saludo contar:</strong><br>" . $saludoContar;

// opcionalmente empezando desde una posición concreta con X longitud de caracteres
$saludoContar2 = substr_count($saludoOriginal, "o", 5, 10);
echo "<br><strong>Saludo contar desde posición 5 con longitud 10:</strong><br>" . $saludoContar2;

//devuelve el número de apariciones de cada caracter dentro de la cadena
$saludoContar3 = count_chars($saludoOriginal,1);
echo "<br><strong>Saludo contar caracteres:</strong><br>";
print_r($saludoContar3);
echo "<br><br>";
foreach($saludoContar3 as $caracter => $cantidad) {
    echo "El caracter '" . chr($caracter) . "' aparece " . $cantidad . " veces.<br>";
}


?>
