<?php
class Usuario
{
    public int  $usurio_id;
    public string $nombre;


    public function __construct($nombre, $usurio_id)
    {
        $this->nombre = $nombre;
        $this->usurio_id = $usurio_id;
    }
    public  function getId()
    {
        return $this->usurio_id;
    }
    public  function getNombre()
    {
        return $this->nombre;
    }
}
