<?php
// Inicializamos la sesión

use ejercicios_clases_interfaces\ejercicio3\classes\Cliente\Cliente;
use ejercicios_clases_interfaces\ejercicio3\classes\Direccion\Direccion;

session_start();
// Continuamos recogiendo las funciones que nos harán falta de includes
require_once($_SERVER['DOCUMENT_ROOT'] . "/ejercicios_clases_interfaces/includes/funciones.php");
require_once("../classes/Cliente.php");
require_once("../classes/Direccion.php");
// Inicializamos nuestro HTML
inicio_html("Pantalla inicial", ["../../styles/general.css", "../../styles/formulario.css"]);
// Controlamos las peticiones que nos realizan a esta pantalla
if ($_SERVER['REQUEST_METHOD'] == 'GET'){
    // Mostramos el formulario que se enviará a el mismo
    ?>
    <h1>Bienvenid@ a la pantalla inicial de la aplicación</h1>
    <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
        <fieldset>
            <legend>Introduce los datos del cliente</legend>
            <label for="nombre">Nombre del cliente</label>
            <input type="text" name="nombre_cliente" id="nombre_cliente">
            <label for="email">Email del cliente</label>
            <input type="email" name="email_cliente" id="email_cliente">
            <label for="tipo_via">Tipo vía</label>
            <input type="text" name="tipo_via" id="tipo_via">
            <label for="nombre_via">Nombre via</label>
            <input type="text" name="nombre_via" id="nombre_via">
            <label for="numero">Número</label>
            <input type="number" name="numero" id="numero">
            <label for="localidad">Localidad</label>
            <input type="text" name="localidad" id="localidad">
            <label for="provincia">Provincia</label>
            <input type="text" name="provincia" id="provincia">
            <label for="pais">Pais</label>
            <input type="text" name="pais" id="pais">
        </fieldset>
        <input type="submit" name="operacion" id="operacion" value="Registrar cliente">
    </form>
    <?php
}
// Si hacemos la petición a la página
else if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    // Hay que sanear los datos y validarlos
    $saneacion_datos = 
    [
        "nombre_cliente" => FILTER_SANITIZE_SPECIAL_CHARS,
        "email_cliente" => FILTER_SANITIZE_SPECIAL_CHARS,
        "tipo_via" => FILTER_SANITIZE_SPECIAL_CHARS,
        "nombre_via" => FILTER_SANITIZE_SPECIAL_CHARS,
        "numero" => FILTER_SANITIZE_NUMBER_INT,
        "localidad" => FILTER_SANITIZE_SPECIAL_CHARS,
        "provincia" => FILTER_SANITIZE_SPECIAL_CHARS,
        "pais" => FILTER_SANITIZE_SPECIAL_CHARS,

    ];
    // Almacenamos los datos saneados
    $datos_saneados = filter_input_array(INPUT_POST, $saneacion_datos);

    // Validamos los datos necesarios
    $datos_saneados["email_cliente"] = filter_var($datos_saneados['email_cliente'], FILTER_VALIDATE_EMAIL);

    // Creamos el objeto Cliente
    $cliente = new Cliente($datos_saneados['email_cliente'], $datos_saneados['nombre_cliente'], new Direccion($datos_saneados['tipo_via'], $datos_saneados['nombre_via'], $datos_saneados['numero'], $datos_saneados['localidad'], $datos_saneados['provincia'], $datos_saneados['pais']));

    if ($cliente){
        $_SESSION['cliente'] = $cliente->getNombreCompleto();
        echo "<h1>Cliente {$cliente->__toString()}</h1>";
        echo "<a href='pantalla_anadir_componentes.php'>Siguiente página</a>";
    }
}
fin_html();
?>