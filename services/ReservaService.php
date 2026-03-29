<?php

require_once __DIR__ . '/../models/Reserva.php';
require_once __DIR__ . '/../models/Mesa.php';
require_once __DIR__ . '/../models/Ubicacion.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../repository/ReservaRepository.php';


class ReservaService
{

    private function convertirMesas($mesas)
    {
        $mesas_asignada = [];
        foreach ($mesas as $mesa) {
            $ubicacion = new Ubicacion($mesa['ubicacion_id'], $mesa['ubicacion']);
            $mesa_nueva = new Mesa($mesa['mesa_id'],  $ubicacion, $mesa['num_mesa'], $mesa['cant_lugares']);
            $mesas_asignada[] = $mesa_nueva;
        }
        return $mesas_asignada;
    }
    public function crearReserva($fecha_inicio, $fecha_actual, $fecha_fin, $cantidadDePersonas, $usurio_id)
    {

        // conexion
        $pdo = new PDO("sqlite:database.db");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $reserva_rep = new ReservaRepository($pdo);
        $mesas_ese_dia = $reserva_rep->obtenerPorFechasUbicacion($fecha_inicio, $fecha_fin, 'A');

        $ub_anterior = "";
        $suma_esp_disponible = 0;
        $mesas = [];
        foreach ($mesas_ese_dia as $mesa) {
            //si la ubicacion anterios es distinta a la actual reseteo 
            if ($ub_anterior !== $mesa['ubicacion']) {
                $ub_anterior = $mesa['ubicacion'];
                $mesas = [];
                $suma_esp_disponible = 0;
            }

            // Acumulo la mesa actual y sumo la cantidad de lugares en la ubicacion
            $mesas[] = $mesa;
            $suma_esp_disponible += $mesa['cant_lugares'];

            //si alcanza con una mesa creo reserva
            if ($mesa['cant_lugares'] >= $cantidadDePersonas) {

                $usuario = new Usuario('Sofia', $usurio_id);
                $reserva = new Reserva($fecha_inicio, $fecha_fin, $fecha_actual, $cantidadDePersonas, $this->convertirMesas($mesas), $usuario);
                $reserva_id = $reserva_rep->guardar($reserva);
                break;
            }
            //si el total acumulado de asientos es suficiente para la cantidad de personas creo reserva
            if (($suma_esp_disponible >= $cantidadDePersonas) && count($mesas) <= 3) {

                $usuario = new Usuario('Sofia', $usurio_id);
                $reserva = new Reserva($fecha_inicio, $fecha_fin, $fecha_actual, $cantidadDePersonas, $this->convertirMesas($mesas), $usuario);
                $reserva_id = $reserva_rep->guardar($reserva);
                break;
            }
        }
        if (!$reserva) {
            throw new Exception("No hay mesas disponibles para la fecha y cantidad de personas solicitada");
        }
        return $reserva_id;
    }
}
