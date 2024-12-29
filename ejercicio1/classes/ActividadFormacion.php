<?php
namespace ejercicios_clases_interfaces\ejercicio1\classes\ActividadFormacion;
use Exception;

class ActividadFormacion {
    // Atributos de la clase.
    private int $codigo;
    private string $titulo;
    private int $horas_presenciales;
    private int $horas_online;
    private int $horas_no_presenciales;
    private string $nivel;
    const NIVEL = ['A', 'B', 'C'];

    // Métodos de la clase.
    // constructor
    function __construct($codigo, $titulo, $horas_presenciales, $horas_online, $horas_no_presenciales, $nivel){
        $this->codigo = $codigo;
        $this->titulo = $titulo;
        $this->horas_presenciales = $horas_presenciales;
        $this->horas_online = $horas_online;
        $this->horas_no_presenciales = $horas_no_presenciales;
        $this->nivel = $nivel;
    }

    // getters
    public function getCodigo(): int {
        return $this->codigo;
    }

    public function getTitulo(): string{
        return $this->titulo;
    }

    public function getHorasPresenciales(): int{
        return $this->horas_presenciales;
    }

    public function getHorasOnline(): int{
        return $this->horas_online;
    }

    public function getHorasNoPresenciales(): int{
        return $this->horas_no_presenciales;
    }

    public function getNivel(): string{
        return $this->nivel;
    }

    // setters
    public function setCodigo(int $codigo){
        $this->codigo = $codigo;
    }

    public function setTitulo(string $titulo){
        $this->titulo = $titulo;
    }

    public function setHorasPresenciales(int $horas_presenciales){
        $this->horas_presenciales = $horas_presenciales;
    }

    public function setHorasOnline(int $horas_online){
        $this->horas_online = $horas_online;
    }

    public function setHorasNoPresenciales(int $horas_no_presenciales){
        $this->horas_no_presenciales = $horas_no_presenciales;
    }

    public function setNivelHoras(string $nivel){
        try{
            if (!array_key_exists(strtolower($nivel), ActividadFormacion::NIVEL)){
                throw new Exception("{$nivel} no es un nivel válido.");
            }
            $this->nivel = $nivel;
        }catch(Exception $e){
            return "Error {$e->getCode()}\nMessage {$e->getMessage()}";
        }
    }

    public function __clone(){
        try{
            if (!$this instanceof ActividadFormacion){
                throw new Exception("El objeto introducino no pertenece a ActividadFormacion");
            }
            $nuevoObjetoClonado = clone $this;
            return $nuevoObjetoClonado;
        }catch(Exception $e){
            return "Error {$e->getCode()}\nMessage {$e->getMessage()}";
        }
    }

    public function __toString(): string{
        return "Codigo {$this->getCodigo()}\nTitulo {$this->getTitulo()}\nHoras presenciales {$this->getHorasPresenciales()}
                \nHoras online {$this->getHOrasOnline()}\nHoras no presenciales {$this->getHorasNoPresenciales()}\nNivel {$this->getNivel()}";
    }
}
?>