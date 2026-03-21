<?php
class Ubicacion
{
    public string $nombre;
    public int $ubicacion_id;

    public function __construct($ubicacion_id, $nombre)
    {
        $this->ubicacion_id = $ubicacion_id;
        $this->nombre = $nombre;
    }
    public function getId()
    {
        return $this->ubicacion_id;
    }
}
