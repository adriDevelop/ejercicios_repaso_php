<?php
// Creación del Espacio de nombres 
namespace ejercicios_clases_interfaces\ejercicio3\classes\Componente;

use Exception;
// Creación de la clase
class Componente{
    // Propiedades de la clase
    protected string $tipo;
    protected string $descripcion;
    protected float $precio;
    // Constante de la que solamente puede pertenecer el tipo
    public const TIPO = ['PLACA', 'MICRO', 'RAM', 'HD'];

    // Constructor
    public function __construct(string $tipo, string $descripcion, float $precio)
    {
                $this->tipo = $tipo;
                $this->descripcion = $descripcion;
                $this->precio = $precio;
    }

    // GETTERS y Setters
    public function getTipo():string
    {
        return $this->tipo;
    }

    public function getDescripcion():string
    {
        return $this->descripcion;
    }

    public function getPrecio():float
    {
        return $this->precio;
    }

    // Método para la representación del objeto en forma de cadena
    public function __toString()
    {
        return "Tipo: {$this->getTipo()}, descripción: {$this->getDescripcion()}, precio: {$this->getPrecio()}";
    }
}
?>