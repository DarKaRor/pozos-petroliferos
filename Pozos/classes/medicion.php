<?php
class Medicion{
    public $valor = 0.0;
    public $fecha = '';
    public $id = 0;

    function __construct($valor,$fecha,$id){
        $this->valor = $valor;
        $this->fecha = $fecha;
        $this->id = $id;
    }
}
