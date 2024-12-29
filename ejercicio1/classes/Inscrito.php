<?php

use ejercicios_clases_interfaces\ejercicio1\classes\DatosFormacion\DatosFormacion;
use ejercicios_clases_interfaces\ejercicio1\classes\ActividadFormacion\ActividadFormacion;
use ejercicios_clases_interfaces\ejercicio1\classes\Alumno\Alumno;

class Inscrito extends Alumno implements DatosFormacion{
    protected int $numero;

    public function __construct(string $nombre, string $apellidos, string $fecha, ActividadFormacion $actividadFormacion, int $numero = 1){
        parent::__construct($nombre, $apellidos, $fecha, $actividadFormacion);
        $this->numero = $numero;
    }

    public function getNumero() {
        return $this->numero;
    }

    public function setNumero(int $numero) {
        $this->numero = $numero;
    }

    public function __toString(){
        return "Numero: {$this->getNumero()}";
    }

    public function obtenerPrecio()
    {
        $antiguedad = '';
        if ($this->getFecha()->diff(new DateTime("now"))->y >= 1){
            $antiguedad.= "Años: " .$this->getFecha()->diff(new DateTime("now"))->y;
        } 
        if($this->getFecha()->diff(new DateTime("now"))->m >= 1){
            $antiguedad.= "Meses: " . $this->getFecha()->diff(new DateTime("now"))->m;
        } 
        if ($this->getFecha()->diff(new DateTime("now"))->d >= 1){
            $antiguedad.= "Dias: " . $this->getFecha()->diff(new DateTime("now"))->d;
        }
        return $antiguedad;
    }

    public function asignarHorario():string
    {
        $horario = '';
        if ($this->getActividad()->getNivel() == 'A' || $this->getActividad()->getNivel() == 'B'){
            $horario = 'De Lunes a Sabado: De 09:00 a 12:00';
        } else if ($this->getActividad()->getNivel() == 'C'){
            $horario = 'De Lunes a Viernes: De 10:00 a 14:00 y de 16:00 a 20:00';
        }
        return $horario;
    }
}
?>