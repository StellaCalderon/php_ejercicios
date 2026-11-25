<?php

function obtenerAsignaturas() {
    $listaAsignaturas = array();
    $archivoProfesores = fopen("profesores.txt", "r");

    if ($archivoProfesores !== false) {
        $lineaActual = fgets($archivoProfesores);
        while ($lineaActual !== false) {
            $lineaLimpia = trim($lineaActual);
            if ($lineaLimpia !== "") {
                $camposProfesor = explode(";", $lineaLimpia);
                if (isset($camposProfesor[4])) {
                    $asignaturasProfesor = explode(",", $camposProfesor[4]);
                    foreach ($asignaturasProfesor as $asignaturaTexto) {
                        $asignaturaLimpia = trim($asignaturaTexto);
                        if ($asignaturaLimpia !== "") {
                            $yaExiste = false;
                            foreach ($listaAsignaturas as $asignaturaGuardada) {
                                if (strcmp(strtolower($asignaturaGuardada), strtolower($asignaturaLimpia)) === 0) {
                                    $yaExiste = true;
                                }
                            }
                            if ($yaExiste === false) {
                                array_push($listaAsignaturas, $asignaturaLimpia);
                            }
                        }
                    }
                }
            }
            $lineaActual = fgets($archivoProfesores);
        }
        fclose($archivoProfesores);
    }

    return $listaAsignaturas;
}

function obtenerProfesoresPorAsignatura($asignaturaBuscada) {
    $profesoresEncontrados = array();
    $archivoProfesores = fopen("profesores.txt", "r");

    if ($archivoProfesores !== false) {
        $lineaActual = fgets($archivoProfesores);
        while ($lineaActual !== false) {
            $lineaLimpia = trim($lineaActual);
            if ($lineaLimpia !== "") {
                $camposProfesor = explode(";", $lineaLimpia);
                if (isset($camposProfesor[4])) {
                    $asignaturasProfesor = explode(",", $camposProfesor[4]);
                    $imparteAsignatura = false;
                    foreach ($asignaturasProfesor as $asignatura) {
                        if (strcmp(strtolower(trim($asignatura)), strtolower(trim($asignaturaBuscada))) === 0) {
                            $imparteAsignatura = true;
                        }
                    }
                    if ($imparteAsignatura === true) {
                        $datosProfesor = array(
                            "id" => trim($camposProfesor[0]),
                            "nombre" => trim($camposProfesor[1]),
                            "apellidos" => trim($camposProfesor[2]),
                            "email" => trim($camposProfesor[3]),
                            "asignaturas" => trim($camposProfesor[4]),
                            "tarifa" => (float)trim($camposProfesor[5])
                        );
                        array_push($profesoresEncontrados, $datosProfesor);
                    }
                }
            }
            $lineaActual = fgets($archivoProfesores);
        }
        fclose($archivoProfesores);
    }

    return $profesoresEncontrados;
}

function comprobarFormatoFecha($fechaTexto) {
    $fechaLimpia = trim($fechaTexto);
    if (!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $fechaLimpia)) {
        return false;
    }

    $partesFecha = explode("-", $fechaLimpia);
    $anio = (int)$partesFecha[0];
    $mes = (int)$partesFecha[1];
    $dia = (int)$partesFecha[2];

    return checkdate($mes, $dia, $anio);
}

function comprobarFormatoHora($horaTexto) {
    $horaLimpia = trim($horaTexto);
    if (!preg_match("/^[0-9]{2}:[0-9]{2}$/", $horaLimpia)) {
        return false;
    }

    $partesHora = explode(":", $horaLimpia);
    $hora = (int)$partesHora[0];
    $minuto = (int)$partesHora[1];

    if ($hora < 0 || $hora > 23) {
        return false;
    }

    if ($minuto < 0 || $minuto > 59) {
        return false;
    }

    return true;
}

function comprobarDisponibilidadProfesor($idProfesor, $fechaClase, $horaInicio, $duracionMinutos) {
    if (comprobarFormatoFecha($fechaClase) === false) {
        return false;
    }

    if (comprobarFormatoHora($horaInicio) === false) {
        return false;
    }

    $diaSemanaClase = (int)date("N", strtotime($fechaClase));

    $partesInicio = explode(":", trim($horaInicio));
    $inicioClaseMinutos = ((int)$partesInicio[0]) * 60 + (int)$partesInicio[1];
    $finClaseMinutos = $inicioClaseMinutos + (int)$duracionMinutos;

    $tieneDisponibilidad = false;
    $archivoDisponibilidad = fopen("disponibilidad.txt", "r");

    if ($archivoDisponibilidad !== false) {
        $lineaActual = fgets($archivoDisponibilidad);
        while ($lineaActual !== false) {
            $lineaLimpia = trim($lineaActual);
            if ($lineaLimpia !== "") {
                $camposDisp = explode(";", $lineaLimpia);
                if (count($camposDisp) >= 4) {
                    $idProfDisp = trim($camposDisp[0]);
                    $diaDisp = (int)trim($camposDisp[1]);
                    $horaIniDisp = trim($camposDisp[2]);
                    $horaFinDisp = trim($camposDisp[3]);

                    if (strcmp($idProfDisp, trim($idProfesor)) === 0 && $diaDisp === $diaSemanaClase) {
                        $partesIniDisp = explode(":", $horaIniDisp);
                        $partesFinDisp = explode(":", $horaFinDisp);
                        $inicioDispMinutos = ((int)$partesIniDisp[0]) * 60 + (int)$partesIniDisp[1];
                        $finDispMinutos = ((int)$partesFinDisp[0]) * 60 + (int)$partesFinDisp[1];

                        if ($inicioClaseMinutos >= $inicioDispMinutos && $finClaseMinutos <= $finDispMinutos) {
                            $tieneDisponibilidad = true;
                        }
                    }
                }
            }
            $lineaActual = fgets($archivoDisponibilidad);
        }
        fclose($archivoDisponibilidad);
    }

    return $tieneDisponibilidad;
}

function calcularFacturacionMes($mesBuscado) {
    $tarifasProfesores = array();
    $nombresProfesores = array();

    $archivoProfesores = fopen("profesores.txt", "r");
    if ($archivoProfesores !== false) {
        $lineaActual = fgets($archivoProfesores);
        while ($lineaActual !== false) {
            $lineaLimpia = trim($lineaActual);
            if ($lineaLimpia !== "") {
                $camposProfesor = explode(";", $lineaLimpia);
                if (count($camposProfesor) >= 6) {
                    $idProf = trim($camposProfesor[0]);
                    $nombresProfesores[$idProf] = trim($camposProfesor[1]) . " " . trim($camposProfesor[2]);
                    $tarifasProfesores[$idProf] = trim($camposProfesor[5]);
                }
            }
            $lineaActual = fgets($archivoProfesores);
        }
        fclose($archivoProfesores);
    }

    $facturacionProfesores = array();

    $archivoClases = fopen("clases.txt", "r");
    if ($archivoClases !== false) {
        $lineaActual = fgets($archivoClases);
        while ($lineaActual !== false) {
            $lineaLimpia = trim($lineaActual);
            if ($lineaLimpia !== "") {
                $camposClase = explode(";", $lineaLimpia);
                if (count($camposClase) >= 7) {
                    $idProf = trim($camposClase[0]);
                    $fechaClase = trim($camposClase[3]);
                    $duracionMinutos = (float)trim($camposClase[5]);
                    $estadoClase = trim($camposClase[6]);

                    $partesFecha = explode("-", $fechaClase);
                    $mesClase = "";
                    if (count($partesFecha) >= 2) {
                        $mesClase = $partesFecha[0] . "-" . str_pad($partesFecha[1], 2, "0", STR_PAD_LEFT);
                    }

                    if (strcmp($mesClase, $mesBuscado) === 0 && strcmp(strtolower($estadoClase), "realizada") === 0) {
                        $tarifaHora = 0;
                        if (isset($tarifasProfesores[$idProf])) {
                            $tarifaHora = $tarifasProfesores[$idProf];
                        }

                        $importeClase = ($duracionMinutos / 60) * $tarifaHora;

                        if (!isset($facturacionProfesores[$idProf])) {
                            $nombreCompleto = $idProf;
                            if (isset($nombresProfesores[$idProf])) {
                                $nombreCompleto = $nombresProfesores[$idProf];
                            }

                            $facturacionProfesores[$idProf] = array(
                                "id" => $idProf,
                                "profesor" => $nombreCompleto,
                                "clases" => 0,
                                "minutos" => 0,
                                "importe" => 0
                            );
                        }

                        $facturacionProfesores[$idProf]["clases"] = $facturacionProfesores[$idProf]["clases"] + 1;
                        $facturacionProfesores[$idProf]["minutos"] = $facturacionProfesores[$idProf]["minutos"] + $duracionMinutos;
                        $facturacionProfesores[$idProf]["importe"] = $facturacionProfesores[$idProf]["importe"] + $importeClase;
                    }
                }
            }
            $lineaActual = fgets($archivoClases);
        }
        fclose($archivoClases);
    }

    return $facturacionProfesores;
}
?>