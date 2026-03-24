<?php

class ReservaRepository
{
    private PDO $pdo;
    private static array $disponibilidadMesasCache;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

        public function obtenerPorFechasUbicacion($fecha_inicio, $fecha_fin,$ubicacion)
    {
        try {
            $cache_clave=$fecha_inicio->format('Y-m-d H:i:s').'_'.$fecha_fin->format('Y-m-d H:i:s').'_'.$ubicacion;
            if(isset(self:: $disponibilidadMesasCache[$cache_clave])){
                return self::$disponibilidadMesasCache[$cache_clave];
            }

            $stmt = $this->pdo->prepare("SELECT m.mesa_id, m.num_mesa, m.cant_lugares, u.nombre AS ubicacion, u.ubicacion_id
        FROM mesa m
        JOIN ubicacion u ON u.ubicacion_id = m.ubicacion_id
        LEFT JOIN reserva_mesa rm ON rm.mesa_id = m.mesa_id
        LEFT JOIN reserva r ON r.reserva_id = rm.reserva_id
        AND r.fecha_inicio < :fecha_fin
        AND r.fecha_fin > :fecha_inicio
        WHERE r.reserva_id IS NULL
        ORDER BY u.nombre;");

        $mesas_disponibles=$stmt->execute(['fecha_fin' => $fecha_fin->format('Y-m-d H:i:s'), 'fecha_inicio' => $fecha_inicio->format('Y-m-d H:i:s')]);
        $mesas_disponibles=$stmt->fetchAll(PDO::FETCH_ASSOC); //recupera todas las filas en un array, por defecto se utiliza PDO::FETCH_BOTH
  
           self::$disponibilidadMesasCache[$cache_clave]=$mesas_disponibles;
              
            return  $mesas_disponibles;
        } catch (Exception $e) {

            throw new Exception("Error al intentar obtener mesas por fechas");
        }
    }

    public function guardar(Reserva $reserva)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO Reserva (cantidad_de_personas,fecha_creacion,fecha_inicio,fecha_fin, usuario_id) VALUES (?,?,?,?,?);");
            $stmt->execute([
                $reserva->cantidadDePersonas,
                $reserva->fecha_creacion->format('Y-m-d H:i:s'),
                $reserva->fecha_inicio->format('Y-m-d H:i:s'),
                $reserva->fecha_fin->format('Y-m-d H:i:s'),
                $reserva->usuario->getId()
            ]);

            $reserva_id = $this->pdo->lastInsertId();


            foreach ($reserva->mesas as $mesa) {
                $stmt = $this->pdo->prepare("INSERT INTO reserva_mesa (reserva_id, mesa_id) VALUES (?, ?)");
                $stmt->execute([$reserva_id, $mesa->getId()]);
            };
            return $reserva_id;
        } catch (Exception $e) {

            throw new Exception("Error al intentar guardar reserva");
        };
    }
}
