<?php
class Reserva
{
    public DateTime $fecha_inicio;
    public DateTime $fecha_fin;
    public DateTime $fecha_creacion;
    public int $cantidadDePersonas;
    /** @var Mesa[] */
    public array $mesas;
    public Usuario $usuario;

    public function __construct($fecha_inicio, $fecha_fin, $fecha_creacion, $cantidadDePersonas, $mesas, $usuario)
    {
        $this->fecha_inicio = $fecha_inicio;
        $this->fecha_fin = $fecha_fin;
        $this->fecha_creacion = $fecha_creacion;
        $this->cantidadDePersonas = $cantidadDePersonas;
        $this->usuario = $usuario;
        $this->mesas = $mesas;
    }
}
