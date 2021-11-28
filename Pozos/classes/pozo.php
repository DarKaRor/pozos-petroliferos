<?php

class Pozo{
    public $nombre = '';
    public $profundidad = 0.0;
    public $mediciones = [];
    public $id = 0;

    function __construct($nombre,$profundidad){
        $this->nombre = $nombre;
        $this->profundidad = $profundidad;
    }
}