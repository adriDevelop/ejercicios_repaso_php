<?php

session_start();

use ejercicios_clases_interfaces\ejercicio1\classes\ActividadFormacion\ActividadFormacion;

require_once($_SERVER['DOCUMENT_ROOT'] . "/ejercicios_clases_interfaces/ejercicio1/classes/ActividadFormacion.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/ejercicios_clases_interfaces/includes/funciones.php");

inicio_html("Practicando clases", ['../styles/formulario.css']);

if ($_SERVER['REQUEST_METHOD'] == 'GET'){
    $fecha = date("j\/n\/Y");
    echo "<h1>Hoy es {$fecha}</h1>";
    ?>
    <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
        <fieldset>
            <legend>Introduzca los datos de la Actividad de formacion</legend>
            <label for="codigo">Codigo</label>
            <input type="number" name="codigo" id="codigo">
            <label for="titulo">Titulo</label>
            <input type="text" name="titulo" id="titulo">
            <label for="horas_presenciales">Horas presenciales</label>
            <input type="number" name="horas_presenciales" id="horas_presenciales">
            <label for="horas_online">Horas online</label>
            <input type="number" name="horas_online" id="horas_online">
            <label for="horas_no_presenciales">Horas no presenciales</label>
            <input type="number" name="horas_no_presenciales" id="horas_no_presenciales">
            <label for="nivel">Nivel</label>
            <select name="nivel" id="nivel">
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
            </select>
        </fieldset>
        <input type="submit" value="hola">
    </form>

    <?php
} else if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    // Generamos un array con los datos de saneamiento para cada valor.
    $array_datos_saneados = [
                            'codigo' => FILTER_SANITIZE_NUMBER_INT,
                            'titulo' => FILTER_SANITIZE_SPECIAL_CHARS,
                            'horas_presenciales' => FILTER_SANITIZE_NUMBER_INT,
                            'horas_online' => FILTER_SANITIZE_NUMBER_INT,
                            'horas_no_presenciales' => FILTER_SANITIZE_NUMBER_INT,
                            'nivel' => FILTER_SANITIZE_SPECIAL_CHARS
    ];

    // Ahora, los saneamos aplicando los filtros anteriormente creados.
    $datos_saneados = filter_input_array(INPUT_POST, $array_datos_saneados);

    // Cuando ya los tengamos saneados, haremos el mismo proceso para filtrarlos y validarlos.
    $array_datos_validacion = [
                              'codigo' => ['filter' => FILTER_VALIDATE_INT,
                                           'options' => FILTER_NULL_ON_FAILURE
                                        ],
                              'titulo' => FILTER_DEFAULT,
                              'horas_presenciales' => ['filter' => FILTER_VALIDATE_INT,
                                                       'options' => FILTER_NULL_ON_FAILURE
                                                    ],
                              'horas_online' => ['filter' => FILTER_VALIDATE_INT,
                                                 'options' => FILTER_NULL_ON_FAILURE
                                                ],
                              'horas_no_presenciales' => ['filter' => FILTER_VALIDATE_INT,
                                                          'options' => FILTER_NULL_ON_FAILURE
                                                        ],
                              'nivel' => FILTER_DEFAULT
                            ];
    
    // Y validamos el array.
    $datos_validados = filter_var_array($datos_saneados, $array_datos_validacion);

    if (!$datos_validados){
        echo "<h3>No se ha encontrado ningún valor o uno de los valores no está bien introducido.</h3>";
        echo "<p>Vuelva a <a href='{$_SERVER['PHP_SELF']}'>intentarlo</a></p>";
    }

    $actividad_formacion = new ActividadFormacion($datos_validados['codigo'], $datos_validados['titulo'], $datos_validados['horas_presenciales'],
                                                  $datos_validados['horas_online'], $datos_validados['horas_no_presenciales'], $datos_validados['nivel']);

    $actividad_formacion_string = $actividad_formacion->__toString();

    $_SESSION['actividad_formacion'] = [$actividad_formacion->getCodigo() => [
                                                                    "string" => $actividad_formacion_string,
                                                                    "codigo" => $datos_validados['codigo'], 
                                                                    "titulo" => $datos_validados['titulo'], 
                                                                    "horas_presenciales" => $datos_validados['horas_presenciales'],
                                                                    "horas_online" => $datos_validados['horas_online'], 
                                                                    "horas_no_presenciales" => $datos_validados['horas_no_presenciales'], 
                                                                    "nivel" => $datos_validados['nivel']
                                                    ]
                                       ];

    echo "<h3>Bienvenido</h3>";
    echo "<h4>{$actividad_formacion_string}</h4>";
    echo "<h2><a href='pagina_genera_alumno.php'>Generar un nuevo Alumno.</a></h2>";

}


fin_html();


?>