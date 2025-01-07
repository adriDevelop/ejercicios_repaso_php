<?php

// Inicio la sesion

use ejercicios_clases_interfaces\ejercicio3\classes\Componente\Componente;

session_start();

// Importa los datos necesarios para el funcionamiento correcto de la aplicación
require_once($_SERVER['DOCUMENT_ROOT'] . "/ejercicios_clases_interfaces/includes/funciones.php");
require_once("../classes/Componente.php");

// Debemos de crear un array con los componentes que hay disponibles
$comp1 = new Componente("PLACA", "Asus ROG STRIX B550-F GAMING WIFI II", 174.99);
$comp2 = new Componente("PLACA", "Asus prime b760-plus", 138.95);
$comp3 = new Componente("MICRO", "Intel Core i5-14400f", 156.95);
$comp4 = new Componente("RAM", "Forgeon cyclone plus", 29.99);
$comp5 = new Componente("HD", "Seagate barracuda 3.5", 56.99);

$componentes_disponibles = [
    "asus_rog_strix_b550" => $comp1,
    "asus_prime_b760" => $comp2,
    "intel_core_i5_14400f" => $comp3,
    "forgeon_cyclone_plus" => $comp4,
    "seagate_barracuda_3.5" => $comp5
];

inicio_html("Pagina agregar componente", ['../../styles/formulario.css', '../../styles/general.css']);
// Controlamos la peticion que nos realice el cliente
if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_SESSION['cliente']){
    $_SESSION['cliente'];
    echo "<h1>Bienvenido {$_SESSION['cliente']}</h1>";
    ?>
    <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
        <fieldset>
            <legend>Introduce los componentes del ordenador</legend>
            <label for="componente">Componente</label>
            <select name="componente" id="componente">
                <?php
                foreach($componentes_disponibles as $key => $value){
                   echo "<option value='{$key}'>{$value->getDescripcion()} | {$value->getPrecio()}€</option>";
                }
                ?>
            </select>
            <label for="cantidad">Cantidad</label>
            <input type="number" name="cantidad" id="cantidad">
        </fieldset>
        <input type="submit" name="operacion" id="operacion">
    </form>
    <?php
} else if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    // Hay que validar los datos que se han introducido
    $opciones_validacion = [
        "componente" => FILTER_SANITIZE_SPECIAL_CHARS,
        "cantidad" => FILTER_SANITIZE_NUMBER_INT
    ];

    // Saneamos los datos
    $datos_saneados = filter_input_array(INPUT_POST, $opciones_validacion);

    // Y validamos los datos que sean necesarios
    $datos_saneados['cantidad'] = filter_var($datos_saneados['cantidad'], FILTER_VALIDATE_INT);

    // Una vez ya tengamos los datos validados, tenemos que almacenar los componentes en el array de 
    // componentes que usaremos para crear nuestro pc
    if (array_key_exists($datos_saneados['componente'], $componentes_disponibles)){
        if (array_key_exists($componentes_disponibles[$datos_saneados['componente']]->getTipo(), Componente::TIPO)){
            $_SESSION['componente'] = [ 
                                        'tipo' => $componentes_disponibles[$datos_saneados['componente']]->getTipo(),
                                        'nombre' =>  $componentes_disponibles[$datos_saneados['componente']]->getDescripcion(),
                                        'precio' =>  $componentes_disponibles[$datos_saneados['componente']]->getPrecio(),
                                        'cantidad' => $datos_saneados['cantidad']
                                      ];
        }
    }

    // Y una vez los tengamos almacenados, los mandamos a la otra pantalla con el presupuesto
    echo "<h2>Perfecto añadimos todo a tu pedido. Mandamos los datos al presupuesto y te los mostramos a 
    continuacion</h2>";

    echo "<a href='pantalla_presupuesto.php'>Mostrar presupuesto</a>";
}
fin_html();
?>