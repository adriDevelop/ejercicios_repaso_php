<?php

session_start();

use ejercicios_clases_interfaces\ejercicio1\classes\ActividadFormacion\ActividadFormacion;
use ejercicios_clases_interfaces\ejercicio1\classes\Socio\Socio;
require_once($_SERVER['DOCUMENT_ROOT'] . "/ejercicios_clases_interfaces/includes/funciones.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/ejercicios_clases_interfaces/ejercicio1/classes/ActividadFormacion.php");

inicio_html("Ingresar nuevo alumno", ["../styles/formulario.css"]);

if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_SESSION['actividad_formacion']){
    echo "<h1>Ingresar un nuevo usuario</h1>";
    foreach ($_SESSION['actividad_formacion'] as $key => $value){
        echo "<h2>{$key}</h2>";
    }
    
    ?>
    <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
        <fieldset>
            <legend>Datos del usuario</legend>
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre"> 
            <label for="apellidos">Apellidos</label>
            <input type="text" name="apellidos" id="apellidos">
            <label for="email">Email</label>
            <input type="email" name="email" id="email">
            <label for="clave">Clave</label>
            <input type="password" name="clave" id="clave">
            <label for="disponibilidad">Disponibilidad</label>
            <input type="text" name="disponibilidad" id="disponibilidad">
            <label for="fecha">Fecha inscripcion</label>
            <input type="date" name="fecha" id="fecha">
            <label for="actividad_formacion">Actividad formacion</label>
            <select name="actividad_formacion" id="actividad_formacion">
                <?php
                    foreach ($_SESSION['actividad_formacion'] as $key => $value) {
                        echo "<option value='{$key}'>{$value['string']}</option>";
                    }
                ?>
            </select>
        </fieldset>
        <input type="submit" name="operacion" id="operacion" value="enviar">
    </form>
    <?php
} else if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    require_once($_SERVER['DOCUMENT_ROOT'] . "/ejercicios_clases_interfaces/ejercicio1/classes/Socio.php");
    // Array con las validaciones para los datos
    $array_saneaciones = [
        'nombre' => FILTER_SANITIZE_SPECIAL_CHARS,
        'apellidos' => FILTER_SANITIZE_SPECIAL_CHARS,
        'fecha' => FILTER_DEFAULT,
        'email' => FILTER_SANITIZE_EMAIL,
        'clave' => FILTER_DEFAULT,
        'disponibilidad' => FILTER_SANITIZE_SPECIAL_CHARS,
        'actividad_formacion' => FILTER_SANITIZE_NUMBER_INT
    ];

    // Validamos los datos con los valores del formulario
    $datos_saneados = filter_input_array(INPUT_POST, $array_saneaciones);

    // Saneamiento
    $datos_saneados['email'] = filter_var($datos_saneados['email'], FILTER_VALIDATE_EMAIL);

    // Comprobaciones
    if (!$datos_saneados){
        echo "<h3>No se han encontrado unos datos correctos.</h3>";
        echo "<a href='{$_SERVER['PHP_SELF']}'>Vuelve a intentarlo</a>";
    }

    if (array_key_exists($datos_saneados['actividad_formacion'], $_SESSION['actividad_formacion'])){
        foreach($_SESSION['actividad_formacion'] as $key => $value){
            if ($key == $datos_saneados['actividad_formacion']){
                $socio = new Socio($datos_saneados['nombre'], $datos_saneados['apellidos'], $datos_saneados['fecha'], 
                          new ActividadFormacion($_SESSION['actividad_formacion'][$key]['codigo'], $_SESSION['actividad_formacion'][$key]['titulo'], $_SESSION['actividad_formacion'][$key]['horas_presenciales'], 
                          $_SESSION['actividad_formacion'][$key]['horas_online'], $_SESSION['actividad_formacion'][$key]['horas_no_presenciales'], $_SESSION['actividad_formacion'][$key]['nivel']), 
                          $datos_saneados['email'], $datos_saneados['clave'], $datos_saneados['disponibilidad']);
            }
        }

        echo "<h1>{$socio}</h1>";
    }
    
}
?>