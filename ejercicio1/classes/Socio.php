<?php

namespace ejercicios_clases_interfaces\ejercicio1\classes\Socio;
require_once($_SERVER['DOCUMENT_ROOT'] . "/ejercicios_clases_interfaces/ejercicio1/classes/Alumno.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/ejercicios_clases_interfaces/ejercicio1/classes/DatosFormacion.php");

use ejercicios_clases_interfaces\ejercicio1\classes\Alumno\Alumno;
use ejercicios_clases_interfaces\ejercicio1\classes\DatosFormacion\DatosFormacion;
use ejercicios_clases_interfaces\ejercicio1\classes\ActividadFormacion\ActividadFormacion;
use DateTime;

class Socio extends Alumno implements DatosFormacion{
    protected string $login;
    protected string $clave;
    protected string $disponibilidad;

    public function __construct(string $nombre, string $apellidos, string $fecha, ActividadFormacion $actividadFormacion,
     string $login, string $clave, string $disponibilidad)
    {
        parent::__construct($nombre, $apellidos, $fecha, $actividadFormacion);
        $this->login = $login;
        $this->clave = password_hash($clave, PASSWORD_DEFAULT);
        $this->disponibilidad = $disponibilidad;
    }

    public function getLogin() {
        return $this->login;
    }

    public function getDisponibilidad() {
        return $this->disponibilidad;
    }

    public function setLogin(string $login) {
        $this->login = $login;
    }

    public function setClave(string $clave) {
        $this->clave = $clave;
    }

    public function setDisponibilidad(string $disponibilidad) {
        $this->disponibilidad = $disponibilidad;
    }

    public function __toString()
    {
        return parent::__toString() . " login: {$this->getLogin()}, disponibilidad: {$this->disponibilidad}";
    }

    public function obtenerPrecio(): string
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

    public function asignarHorario(): string
    {
        $horario = '';
        if ($this->getActividad()->getHorasPresenciales() >= 100 && $this->getActividad()->getHorasPresenciales() <= 200){
            $horario = "Disponibilidad de mañana: De lunes a Viernes de 09:00 a 13:00\nDisponibilidad de tarde: De lunes a Viernes de 16:00 a 20:00";
        } else if ($this->getActividad()->getHorasPresenciales() > 100){
            $horario = "Disponibilidad de mañana: De Lunes a  Viernes de 09:00 a 11:00\n Disponibilidad de tarde: De Lunes a Viernes de 16:00 a 18:00";
        } 
        
        if ($this->getActividad()->getHorasOnline() >= 50 && $this->getActividad()->gethorasOnline() <= 100){
            $horario.= "\nDisponibilidad Sábados y Domingos: De 09:00 a 12:00";
        } else if ($this->getActividad()->getHorasOnline() < 50){
            $horario.= "\nDisponibilidad Sábados: De 08:00 a 10:00";
        }

        return $horario;
    }
}
?>