<?php
/*
    SCRIPT DE AUTENTICACIÓN
    -----------------------
    Gestionar datos de la BBDD:
        - Tenemos que autenticarnos como USUARIO de la base de datos.
            - Presentar un formulario de autenticación.
            - Abrir conexión con la base de datos con los datos del usuario.

    Despues, mandaremos al usuario logueado a la pantalla principal.
*/

// Inicio de SESSION
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . "/ejercicios_clases_interfaces/includes/funciones.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/ejercicios_clases_interfaces/includes/03jwt_include.php");

// Iniciamos nuestro HTML
inicio_html("Logueo de usuario", ['../../styles/general.css', '../../styles/formulario.css']);

// Comprobamos la petición al servidor
if ($_SERVER['REQUEST_METHOD'] == 'GET'){
    echo "<h1>Inicio de sesión</h1>";
    ?>
    <!-- Mostramos el formulario HTML -->
     <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
        <fieldset>
            <legend>Introduce los datos para el logueo del usuario:</legend>
            <label for="email">Usuario</label>
            <input type="text" name="email" id="email" required>
            <label for="clave">Clave</label>
            <input type="password" name="clave" id="clave" required>
        </fieldset>
        <input type="submit" name="operacion" id="operacion" value="Iniciar sesión">
     </form>
    <?
} else if ($_SERVER['REQUEST_METHOD'] == 'POST'){

        // Saneación de datos del formulario
        $usuario_introducido = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS);
        $clave_introducida = filter_input(INPUT_POST, 'clave', FILTER_SANITIZE_SPECIAL_CHARS);

        $_SESSION['usuario'] = $usuario_introducido;
        $_SESSION['clave'] = $clave_introducida;

        echo "<a href='pantalla_principal.php'>Ir a la pantalla principal</a>";
}
?>