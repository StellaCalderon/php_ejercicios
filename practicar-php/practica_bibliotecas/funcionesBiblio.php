<?php

// Saca las categorías únicas de libros.txt
function obtenerCategorias() {
    $listaCategorias = array();
    $archivoLibros = fopen("libros.txt", "r");

    if ($archivoLibros !== false) {
        $lineaActual = fgets($archivoLibros);
        while ($lineaActual !== false) {
            $lineaLimpia = trim($lineaActual);
            if ($lineaLimpia !== "") {
                $camposLibro = explode(";", $lineaLimpia);
                if (isset($camposLibro[3])) {
                    $categoriaLimpia = trim($camposLibro[3]);
                    $yaExiste = false;
                    foreach ($listaCategorias as $catGuardada) {
                        if (strcmp(strtolower($catGuardada), strtolower($categoriaLimpia)) === 0) {
                            $yaExiste = true;
                        }
                    }
                    if ($yaExiste === false && $categoriaLimpia !== "") {
                        array_push($listaCategorias, $categoriaLimpia);
                    }
                }
            }
            $lineaActual = fgets($archivoLibros);
        }
        fclose($archivoLibros);
    }

    return $listaCategorias;
}

// Devuelve los libros de una categoría que tengan stock > 0
function obtenerLibrosPorCategoria($categoriaBuscada) {
    $librosEncontrados = array();
    $archivoLibros = fopen("libros.txt", "r");

    if ($archivoLibros !== false) {
        $lineaActual = fgets($archivoLibros);
        while ($lineaActual !== false) {
            $lineaLimpia = trim($lineaActual);
            if ($lineaLimpia !== "") {
                $camposLibro = explode(";", $lineaLimpia);
                if (count($camposLibro) >= 5) {
                    $catLibro = trim($camposLibro[3]);
                    $stock = (int)trim($camposLibro[4]);
                    if (strcmp(strtolower($catLibro), strtolower(trim($categoriaBuscada))) === 0 && $stock > 0) {
                        $datosLibro = array(
                            "isbn" => trim($camposLibro[0]),
                            "titulo" => trim($camposLibro[1]),
                            "autor" => trim($camposLibro[2]),
                            "stock" => $stock
                        );
                        array_push($librosEncontrados, $datosLibro);
                    }
                }
            }
            $lineaActual = fgets($archivoLibros);
        }
        fclose($archivoLibros);
    }

    return $librosEncontrados;
}

function comprobarFormatoFecha($fechaTexto) {
    $fechaLimpia = trim($fechaTexto);
    if (!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $fechaLimpia)) {
        return false;
    }

    $partesFecha = explode("-", $fechaLimpia);
    return checkdate((int)$partesFecha[1], (int)$partesFecha[2], (int)$partesFecha[0]);
}

// Comprueba que la fecha de recogida no caiga en domingo (7) y que el socio no esté SANCIONADO
function comprobarSocioYFecha($numSocio, $fechaSalida) {
    if (comprobarFormatoFecha($fechaSalida) === false) {
        return false;
    }

    $diaSemana = (int)date("N", strtotime($fechaSalida));
    if ($diaSemana === 7) {
        return false;
    }

    $socioActivo = false;
    $archivoSocios = fopen("socios.txt", "r");
    if ($archivoSocios !== false) {
        $lineaActual = fgets($archivoSocios);
        while ($lineaActual !== false) {
            $lineaLimpia = trim($lineaActual);
            if ($lineaLimpia !== "") {
                $camposSocio = explode(";", $lineaLimpia);
                if (count($camposSocio) >= 5) {
                    if (strcmp(trim($camposSocio[0]), trim($numSocio)) === 0 && strcmp(strtoupper(trim($camposSocio[4])), "ACTIVO") === 0) {
                        $socioActivo = true;
                    }
                }
            }
            $lineaActual = fgets($archivoSocios);
        }
        fclose($archivoSocios);
    }

    return $socioActivo;
}

// Calcula las multas (1.50€ por día de retraso) de los libros devueltos en un mes (AAAA-MM)
function calcularMultasMes($mesBuscado) {
    $nombresSocios = array();
    $archivoSocios = fopen("socios.txt", "r");
    if ($archivoSocios !== false) {
        $lineaActual = fgets($archivoSocios);
        while ($lineaActual !== false) {
            $lineaLimpia = trim($lineaActual);
            if ($lineaLimpia !== "") {
                $camposSocio = explode(";", $lineaLimpia);
                if (count($camposSocio) >= 3) {
                    $nombresSocios[trim($camposSocio[0])] = trim($camposSocio[1]) . " " . trim($camposSocio[2]);
                }
            }
            $lineaActual = fgets($archivoSocios);
        }
        fclose($archivoSocios);
    }

    $multasSocios = array();
    $archivoPrestamos = fopen("prestamos.txt", "r");
    if ($archivoPrestamos !== false) {
        $lineaActual = fgets($archivoPrestamos);
        while ($lineaActual !== false) {
            $lineaLimpia = trim($lineaActual);
            if ($lineaLimpia !== "") {
                $campos = explode(";", $lineaLimpia);
                if (count($campos) >= 7) {
                    $numSocio = trim($campos[1]);
                    $fechaTope = trim($campos[4]);
                    $fechaDev = trim($campos[5]);
                    $estado = trim($campos[6]);

                    if (strcmp(strtolower($estado), "devuelto") === 0 && $fechaDev !== "") {
                        $partesDev = explode("-", $fechaDev);
                        $mesDev = $partesDev[0] . "-" . str_pad($partesDev[1], 2, "0", STR_PAD_LEFT);

                        if (strcmp($mesDev, $mesBuscado) === 0) {
                            $tsTope = strtotime($fechaTope);
                            $tsDev = strtotime($fechaDev);
                            $diasRetraso = 0;
                            if ($tsDev > $tsTope) {
                                $diasRetraso = (int)(($tsDev - $tsTope) / 86400);
                            }

                            if ($diasRetraso > 0) {
                                $importeMulta = $diasRetraso * 1.50;
                                if (!isset($multasSocios[$numSocio])) {
                                    $multasSocios[$numSocio] = array(
                                        "socio" => $numSocio,
                                        "nombre" => isset($nombresSocios[$numSocio]) ? $nombresSocios[$numSocio] : $numSocio,
                                        "librosRetraso" => 0,
                                        "diasTotales" => 0,
                                        "multaTotal" => 0
                                    );
                                }
                                $multasSocios[$numSocio]["librosRetraso"]++;
                                $multasSocios[$numSocio]["diasTotales"] += $diasRetraso;
                                $multasSocios[$numSocio]["multaTotal"] += $importeMulta;
                            }
                        }
                    }
                }
            }
            $lineaActual = fgets($archivoPrestamos);
        }
        fclose($archivoPrestamos);
    }

    return $multasSocios;
}
?>