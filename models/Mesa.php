<?php
class Mesa
{
    public Ubicacion $ubicacion;
    public  int $numeroDeMesa;
    public int $cantidadPersonas;
    public int $mesa_id;


    public function __construct($mesa_id, $ubicacion, $numeroDeMesa, $cantidadPersonas)
    {
        $this->mesa_id = $mesa_id;
        $this->numeroDeMesa = $numeroDeMesa;
        $this->cantidadPersonas = $cantidadPersonas;
        $this->ubicacion = $ubicacion;
    }


    public function getId()
    {
        return $this->mesa_id;
    }
}
