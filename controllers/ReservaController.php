<?php
require_once __DIR__ . '/../services/ReservaService.php';

class ReservaController
{


    private function fechaValida($fecha_inicio_reserva, $fecha_hoy)
    {
        if ($fecha_inicio_reserva < $fecha_hoy) {
            return false;
        }
        //reviso 15 min
        $seg_rest = $fecha_inicio_reserva->getTimestamp() - $fecha_hoy->getTimestamp();
        if ($seg_rest < 900) { // 15 min= 900 segundos
            throw new Exception("La reserva debe ser con mas de 15 minutos de anticipacion");
        }
        // si el dia es L-V de 10 a 24, sábado de 22 a 2AM, domingo de 12 a 16]
        $dia = $fecha_inicio_reserva->format('N');
        $hora = $fecha_inicio_reserva->format('G');

        if ($dia >= 1 && $dia <= 5) { // lunes a viernes
            if ($hora < 10 || $hora >= 24) {
                throw new Exception("La fecha debe ser valida  L-V de 10 a 24");
            }
            return true;
        }

        if ($dia == 6) { //sabado
            if ($hora >= 22) {
                return true;
            }
            throw new Exception("La fecha debe ser valida sábado de 22 a 2AM");
        }
        if ($dia == 7) {
            if ($hora < 2) {
                return true;
            }

            if ($hora >= 12 && $hora < 16) {
                return true;
            }
            throw new Exception("La fecha debe ser valida domingo de 12 a 16");
        }
    }

    function Crear()
    {

        $cantidad_personas = (int)$_POST['cantidadPersonas'];
        $fecha_inicio = new DateTime($_POST['fecha']);
        $fecha_hoy = new DateTime(date("Y-m-d H:i:s"));
        $fecha_fin = clone $fecha_inicio;
        $fecha_fin->modify('+2 hours');
        $usurio_id = 5;

        if ($cantidad_personas < 1) {
            throw new Exception("La cantidad de personas debe ser un numero valido ");
        }

        if (!$this->fechaValida($fecha_inicio, $fecha_hoy)) {
            throw new Exception("La fecha debe ser valida");
        }
        try {
            $service = new ReservaService();
            $reserva_id = $service->crearReserva($fecha_inicio, $fecha_hoy, $fecha_fin, $cantidad_personas, $usurio_id);
            echo  "Creado exitosamente", $reserva_id;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
