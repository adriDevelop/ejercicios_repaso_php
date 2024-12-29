<?php
namespace ejercicios_clases_interfaces\ejercicio1\classes\Alumno;


use ejercicios_clases_interfaces\ejercicio1\classes\ActividadFormacion\ActividadFormacion;
use DateTime;

abstract class Alumno{
    protected string $nombre;
    protected string $apellidos;
    protected DateTime $fecha;
    protected ActividadFormacion $actividadFormacion;

    public function __construct(string $nombre, string $apellidos, string $fecha, ActividadFormacion $actividadFormacion)
    {
     $this->nombre = $nombre;
     $this->apellidos = $apellidos;
     $this->fecha = DateTime::createFromFormat("Y-m-d", $fecha);
     $this->actividadFormacion = $actividadFormacion;   
    }

    public function getNombre():string {
        return $this->nombre;
    }

    public function getApellidos():string {
        return $this->apellidos;
    }

    public function getFecha():DateTime {
        return $this->fecha;
    }

    public function getActividad():ActividadFormacion {
        return $this->actividadFormacion;
    }

    public function setNombre(string $nombre) {
        $this->nombre = $nombre;
    }

    public function setApellidos(string $apellidos) {
        $this->apellidos = $apellidos;
    }

    public function setFecha(string $fecha) {
        $this->fecha = $fecha;
    }

    public function calcularAntiguedad() {
        $antiguedad = '';
        if ($this->getFecha()->diff(new DateTime("now"))->y >= 1){
            $antiguedad.= "AÑOS: " .$this->getFecha()->diff(new DateTime("now"))->y;
        } else if($this->getFecha()->diff(new DateTime("now"))->m >= 1){
            $antiguedad.= "meses: " . $this->getFecha()->diff(new DateTime("now"))->m;
        } else if ($this->getFecha()->diff(new DateTime("now"))->d >= 1){
            $antiguedad.= "diAS: " . $this->getFecha()->diff(new DateTime("now"))->d;
        }
        return ;
        
    }

    public function setActividadFormacion(ActividadFormacion $actividadFormacion) {
        $this->actividadFormacion = $actividadFormacion;
    }

    public function __toString()
    {
        return "Nombre alumno: {$this->nombre}, Apellidos alumno: {$this->apellidos}, Antiguedad: {$this->calcularAntiguedad()}, Actividad formacion: {$this->actividadFormacion->__toString()}";
    }
}
?>