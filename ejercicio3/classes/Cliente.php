<?php

// Espacio de nombres
namespace ejercicios_clases_interfaces\ejercicio3\classes\Cliente;

// Uso del espacio de nombres de la clase Direccion para que podamos importar la clase y hacer uso de ella para el atributo dirección de la clase Cliente
use ejercicios_clases_interfaces\ejercicio3\classes\Direccion\Direccion;

class Cliente{
    // Propiedades de la clase
    protected string $email;
    protected string $nombre_completo;
    protected Direccion $direccion;

    // Constructor de la clase
    public function __construct(string $email, string $nombre_completo, Direccion $direccion)
    {
        $this->email = $email;
        $this->nombre_completo = $nombre_completo;
        $this->direccion = $direccion;
    }

    // Getters y Setters
    public function getEmail():string 
    {
        return $this->email;
    }

    public function getNombreCompleto():string
    {
        return $this->nombre_completo;
    }

    public function getDireccion():string
    {
        return $this->direccion->__toString();
    }

    // Método que representa el objeto en cadena de caracteres
    public function __toString():string
    {
        return "Email: {$this->getEmail()}, nombre completo: {$this->getNombreCompleto()}, dirección: {$this->getDireccion()}";
    }
}
?>