<?php

function crearText($nombre, $valor)
{
    $html = '<input type="text" name="' . $nombre . '" value="' . $valor . '">';
    return $html;
}
function rangoFechasValido($fechaInicio, $fechaFin)
{
    $f1 = DateTime::createFromFormat('d/m/y', $fechaInicio);
    $f2 = DateTime::createFromFormat('d/m/y', $fechaFin);

    if ($f1 <= $f2) {
        return true;
    } else {
        return false;
    }
}

function leerFichero($nombreFichero, $separador)
{
    $filas = array();

    if (file_exists($nombreFichero)) {
        $fichero = @fopen($nombreFichero, "r");
        if ($fichero) {
            while (!feof($fichero)) {
                $linea = trim(fgets($fichero));
                if ($linea != "") {
                    $filas[] = explode($separador, $linea);
                }
            }
            fclose($fichero);
        }
    }

    return $filas;
}

// Comprueba si una matrícula aparece en la primera columna ($fila[0]) de un fichero de autorizados
function estaEnFichero($matricula, $nombreFichero)
{
    // Leemos todas las líneas del fichero separadas por espacio
    $filas = leerFichero($nombreFichero, " ");
    foreach ($filas as $fila) {
        // Si la matrícula de esa línea coincide con la que buscamos, cortamos y devolvemos true
        if ($fila[0] == $matricula) {
            return true;
        }
    }
    // Si recorremos todo el fichero y no aparece, devolvemos false
    return false;
}

// Comprueba si un coche tiene permiso de residente/hotel vigente en la fecha en que circuló ($fechaCoche)
function tienePermisoResidente($matricula, $fechaCoche)
{
    // Leemos el fichero de residentes y hoteles (formato: matricula lugar fechaInicio fechaFin)
    $filas = leerFichero("txt/residentesYHoteles.txt", " ");
    foreach ($filas as $fila) {
        // Primero comprobamos si la línea pertenece a esa matrícula
        if ($fila[0] == $matricula) {
            // En este fichero las fechas vienen como AAAA/MM/DD (ej: 2016/12/23), por eso usamos 'Y/m/d'
            $fIniPermiso = DateTime::createFromFormat('Y/m/d', $fila[2]);
            $fFinPermiso = DateTime::createFromFormat('Y/m/d', $fila[3]);
            // Comprobamos si el día que pasó el coche está dentro del rango permitido
            if ($fIniPermiso <= $fechaCoche && $fechaCoche <= $fFinPermiso) {
                return true;
            }
        }
    }
    // Si no está en el fichero o su permiso estaba fuera de fecha, devolvemos false
    return false;
}
$fechaInicio = "";
$fechaFin = "";


$errores = array(false, false);

// 2. Comprobamos si se ha pulsado el botón Enviar
if (isset($_POST['buscar'])) {
    $fechaInicio = trim($_POST['fechaInicio']);
    $fechaFin = trim($_POST['fechaFin']);
    // Comprobamos fecha inicial por separado
    if (empty($fechaInicio)) {
        $errores[0] = "Debes rellenar la fecha inicial.";
    } elseif (!DateTime::createFromFormat('d/m/y', $fechaInicio)) {
        $errores[0] = "Formato incorrecto (usa dd/mm/aa).";
    }

    // Comprobamos fecha final por separado
    if (empty($fechaFin)) {
        $errores[1] = "Debes rellenar la fecha final.";
    } elseif (!DateTime::createFromFormat('d/m/y', $fechaFin)) {
        $errores[1] = "Formato incorrecto (usa dd/mm/aa).";
    } elseif ($errores[0] == false && !rangoFechasValido($fechaInicio, $fechaFin)) {
        $errores[1] = "La fecha inicial debe ser anterior o igual a la final.";
    }

    //esto si en vez de ponerlo debajo lo quiero poner al lado 

    //   if (empty($fechaInicio) || empty($fechaFin)) {
    //      $errores[0] = "Debes rellenar las dos fechas.";
    // } elseif (!rangoFechasValido($fechaInicio, $fechaFin)) {
    //        $errores[1] = "La fecha inicial tiene que ser anterior o igual a la final.";
    //   }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="estilos.css">
</head>

<body>
    <h3>Listado Infractores</h3>
    <form action="listadoInfractores.php" method="post">

        <!-- Esto si lo quiero poner abajo (evidentemente luego poner btn buscar)
   <!--     <p>Fecha Inicial (dd/mm/aa): <?php //echo crearText("fechaInicio", $fechaInicio) 
                                            ?></p>
        <p>Fecha Final (dd/mm/aa): <? //  php echo crearText("fechaFin", $fechaFin) 
                                    ?></p>

        <?php
        //if ($errores[0] != false) {
        // echo '<p class="error">' . $errores[0] . '</p>';
        //}
        //if ($errores[1] != false) {
        //    echo '<p class="error">' . $errores[1] . '</p>';
        //}
        ?>-->

        <p>
            Fecha Inicial (dd/mm/aa): <?php echo crearText("fechaInicio", $fechaInicio); ?>
            <?php 
            if ($errores[0] != false) echo '<span class="error">' . $errores[0] . '</span>'; 
            ?>
        </p>
        <p>
            Fecha Final (dd/mm/aa): <?php echo crearText("fechaFin", $fechaFin); ?>
            <?php if ($errores[1] != false) echo '<span class="error">' . $errores[1] . '</span>'; ?>
        </p>

        <p><input type="submit" name="buscar" value="Buscar"></p>
    </form>
    <?php
    if (isset($_POST['buscar']) && $errores[0] == false && $errores[1] == false) {
        $fechaInicio = DateTime::createFromFormat('d/m/y', $fechaInicio);
        $fechaFin = DateTime::createFromFormat('d/m/y', $fechaFin);
        // el separador ahora es el espacio pero puede ser otro
        $vehiculos = leerFichero("txt/vehiculos.txt", " ");
    

            // --- VERSIÓN DE PRUEBA ANTERIOR (COMENTADA) ---
            // Al principio hacíamos este bucle solo para comprobar que leía bien el fichero
            // y que filtraba correctamente por fechas. Mostraba TODOS los coches del rango
            // (incluido el 1044-BPF del 23/12/16 que era eléctrico) porque todavía no habíamos
            // comprobado los ficheros de permisos (taxis, residentes, logística, etc.).
            /*

          //en este caso está en la posicion 3 la fecha(empieza en 0)
         //si estuviese en el formato predeterminado sería lo de abajo


            // $fechaCoche= new DateTime($vehiculo[3]);
        foreach ($vehiculos as $vehiculo) {
            $fechaCoche = DateTime::createFromFormat('d/m/y', $vehiculo[3]);

                        //comprueba si $fechaCoche está entre $fechaInicio y $fechaFin

            if ($fechaInicio <= $fechaCoche && $fechaCoche <= $fechaFin) {
                echo "<p>" . $vehiculo[0] . " - " . $vehiculo[3] . " - " . $vehiculo[5] . "</p>";
            }
        }
        */
        // --- VERSIÓN DEFINITIVA (FILTRANDO PERMISOS Y MOSTRANDO TABLA DE INFRACTORES) ---
        // Abrimos la tabla HTML antes del bucle para no repetir la cabecera en cada coche
        echo "<table border='1'>";
        // Primera fila con los títulos de las columnas
        echo "<tr><th>Matrícula</th><th>Propietario</th><th>Dirección</th><th>Fecha</th><th>Hora</th><th>Combustible</th></tr>";

        foreach ($vehiculos as $vehiculo) {
            // En este caso está en la posición 3 la fecha (empieza en 0)
            $fechaCoche = DateTime::createFromFormat('d/m/y', $vehiculo[3]);

            // Comprueba si $fechaCoche está entre $fechaInicio y $fechaFin
            if ($fechaInicio <= $fechaCoche && $fechaCoche <= $fechaFin) {
                $matricula = $vehiculo[0];
                $hora = $vehiculo[4];
                $combustible = $vehiculo[5];

                // Empezamos suponiendo que NO tiene permiso (es infractor salvo que demuestre lo contrario)
                $tienePermiso = false;

                // 1. Se libra si es eléctrico o está en taxis, servicios o EMT
                if ($combustible == "eléctrico" ||
                    estaEnFichero($matricula, "txt/taxis.txt") ||
                    estaEnFichero($matricula, "txt/servicios.txt") ||
                    estaEnFichero($matricula, "txt/vehiculosEMT.txt")) {
                    $tienePermiso = true;
                }
                // 2. Se libra si es logística Y pasó entre las 06:00 y las 11:00
                elseif (estaEnFichero($matricula, "txt/logistica.txt") && $hora >= "06:00" && $hora <= "11:00") {
                    $tienePermiso = true;
                }
                // 3. Se libra si tiene permiso de residente/hotel vigente ese día
                elseif (tienePermisoResidente($matricula, $fechaCoche)) {
                    $tienePermiso = true;
                }

                // Si después de mirar todas las excepciones sigue sin tener permiso (!), lo pintamos en la tabla
                if (!$tienePermiso) {
                    echo "<tr>";
                    echo "<td>" . $vehiculo[0] . "</td>";
                    echo "<td>" . $vehiculo[1] . "</td>";
                    echo "<td>" . $vehiculo[2] . "</td>";
                    echo "<td>" . $vehiculo[3] . "</td>";
                    echo "<td>" . $vehiculo[4] . "</td>";
                    echo "<td>" . $vehiculo[5] . "</td>";
                    echo "</tr>";
                }
            }
        }
        // Cerramos la tabla al terminar de recorrer todos los vehículos
        echo "</table>";
    }
    ?>
</body>

</html>