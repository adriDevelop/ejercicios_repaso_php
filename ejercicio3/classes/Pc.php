<?php
// Importación de clase componente necesaria para la creación del objeto
use ejercicios_clases_interfaces\ejercicio3\classes\Componente\Componente;
// Creación de la clase
class Pc{
    // Propiedades de clase
    protected int $numero_serie;
    protected string $descripcion;
    protected int $tiempo_construccion;
    protected array $componentes;

    // Constructor de la clase
    public function __construct(int $ns, string $d, int $tc, array $componentes)
    {
        $this->numero_serie = $ns;
        $this->descripcion = $d;
        $this->tiempo_construccion = $tc;
        $this->componentes = $componentes;
    }

    // Getters y Setters
    public function getNumeroSerie(): int
    {
        return $this->numero_serie;
    }
    public function getDescripcion(): string
    {
        return $this->descripcion;
    }
    public function getTiempoConstruccion(): int
    {
        return $this->tiempo_construccion;
    }
    public function getComponentes(): string
    {
        $componentes_string = '';
        foreach($this->componentes as $componente){
            $componentes_string .= " $componente \n";
        }
        return $componentes_string;
    }

    // Método que clona el objeto en otro
    public function __clone(): Pc
    {
        $objeto_clonado = $this->__clone();
        return $objeto_clonado;
    }

    // Método que devuelve el objeto en una línea de string
    public function __toString():string
    {
        return "Número serie: {$this->getNumeroSerie()}, descripción: {$this->getDescripcion()}, tiempo construcción: {$this->getTiempoConstruccion()}, componentes: {$this->getComponentes()}";
    }
}
?>