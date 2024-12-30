<?php
// Espacio de nombres
namespace ejercicios_clases_interfaces\ejercicio3\classes\Direccion;

class Direccion{
    // Atributos de la clase dirección
    protected string $tipo_via;
    protected string $nombre_via;
    protected int $numero;
    protected string $localidad;
    protected string $provincia;
    protected string $pais;

    // Constructor de la clase
    public function __construct(string $tipo_via, string $nombre_via, int $numero, string $localidad, string $provincia, string $pais){
        $this->tipo_via = $tipo_via;
        $this->nombre_via = $nombre_via;
        $this->numero = $numero;
        $this->localidad = $localidad;
        $this->provincia = $provincia;
        $this->pais = $pais;
    }

    // Getters y Setters
    public function getTipoVia(): string{
        return $this->tipo_via;
    }

    public function getNombreVia(): string{
        return $this->nombre_via;
    }

    public function getNumero(): int{
        return $this->numero;
    }

    public function getLocalidad(): string{
        return $this->localidad;
    }

    public function getProvincia(): string{
        return $this->provincia;
    }

    public function getPais(): string{
        return $this->pais;
    }

    // Método clonación de objetos
    public function __clone()
    {
        $objeto_clonado = $this->__clone();
    }

    // Método que muestra el objeto en una línea
    public function __toString(): string
    {
        return "Tipo via: {$this->getTipoVia()}, nombre vía: {$this->getNombreVia()}, número: {$this->getNumero()}, localidad: {$this->getLocalidad()}, provincia: {$this->getProvincia()}, pais: {$this->getPais()}";
    }
}
?>