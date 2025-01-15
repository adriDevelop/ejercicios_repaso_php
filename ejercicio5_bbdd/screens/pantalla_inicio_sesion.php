<?php
// Iniciamos sesion
session_start();

ob_start();

// Importamos cosas que necesitamos
require_once($_SERVER['DOCUMENT_ROOT'] . "/ejercicios_clases_interfaces/includes/funciones.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/ejercicios_clases_interfaces/includes/03jwt_include.php");

// Iniciamos HTML
inicio_html("Pantalla inicio sesión", ['../../styles/general.css', '../../styles/formulario.css']);

// Comprobamos peticiones al servidor
if ($_SERVER['REQUEST_METHOD'] == 'GET'){
    echo "<h1>Inicio de sesión</h1>";
    ?>
    <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
        <fieldset>
            <legend>Inicia sesión:</legend>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>
            <label for="clave">Clave</label>
            <input type="password" name="clave" id="clave" required>
        </fieldset>
        <input type="submit" name="operacion" id="operacion" value="Iniciar Sesión">
    </form>
    <?php
} else if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    // Sanear y validar datos
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);

    $clave = $_POST['clave'];

    // Conexión con la base de datos
    try{
        // Inicializar la conexión
        $bbdd_nombre = "mysql";
        $bbdd_usuario = "usuario";
        $bbdd_clave = "usuario";
        $bbdd_schema = "tiendaol";
        $bbdd_puerto = 3306;

        $cbd = new mysqli($bbdd_nombre, $bbdd_usuario, $bbdd_clave, $bbdd_schema, $bbdd_puerto);

        // Preparamos consulta sql
        $sql = "SELECT nif, nombre, apellidos, clave, iban, telefono, email, ventas";
        $sql.= " FROM cliente";
        $sql.= " WHERE email = ?";
        $stmt = $cbd->prepare($sql);

        // Vincular datos
        $stmt->bind_param("s", $email);

        // Ejecutamos consulta
        $stmt->execute();

        // Obtenemos resultado de la consulta
        $resultset = $stmt->get_result();

        if ($resultset->num_rows == 1){
            // Almacenamos resultado en un array cliente
            $cliente = $resultset->fetch_assoc();
            // Comprobamos validacion de usuario con la clave
            if (password_verify($clave, $cliente['clave'])){
                // Creamos el payload
                $payload = [
                    'nif' => $cliente['nif'],
                    'nombre' => $cliente['nombre'] . ' ' . $cliente['apellidos'],
                    'email' => $cliente['email']
                ];
                // Generamos token
                $jwt = generar_token($payload);
                setcookie("jwt", $jwt, time() + 30 * 60);

                // Mandamos al usuario a la siguiente ventana
                header("Location: pantalla_listado_direcciones.php");
            }else {
                echo "<h2>No se ha podido iniciar sesión ya que la clave no es correcta</h2>";
                echo "<a href='pantalla_inicio_sesion.php'>Volver a intentar</a>";
            }
        }
    }catch(Exception $e){
        echo "Mensaje error: " . $e->getMessage();
    }
}

ob_flush();
?>