<?php

// Inicializamos la sesion para poder recuperar todos los datos de la sesion
session_start();

// Ahora, requerimos usar las funciones que tenemos en nuestro includes
require_once($_SERVER['DOCUMENT_ROOT'] . "/ejercicios_clases_interfaces/includes/funciones.php");

// Controlamos la peticion que hacen al servidor
inicio_html("Presupuesto", ['../../styles/formulario.css', '../../styles/general.css', '../../styles/tablas.css']);
if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_SESSION['componente']){
    echo "<h1>Bienvenido al presupuesto</h1>";
    // Mostramos una tabla para el presupuesto final
    ?>
    <table>
        <thead>
            <tr>
            <th>Tipo</th>
            <th>Nombre</th>
            <th>Precio Unidad</th>
            <th>Cantidad</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach($_SESSION['componente'] as $componente){
                echo "<tr>";
                    echo "<td>{$componente}</td>";
                echo "</tr>";
            }
            
            ?>
        </tbody>
    </table>
    <?php
}
fin_html();
?>