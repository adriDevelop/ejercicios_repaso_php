<?php

// Iniciamos ob
ob_start();

// Importamos lo que necesitamos
require_once($_SERVER['DOCUMENT_ROOT'] . "/ejercicios_clases_interfaces/includes/funciones.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/ejercicios_clases_interfaces/includes/03jwt_include.php");

// Iniciamos HTML
inicio_html("Pantalla inicio sesión", ['../../styles/general.css', '../../styles/formulario.css']);

// Controlamos la petición que haga el usuario
if ($_SERVER['REQUEST_METHOD'] == 'GET'){
    echo "<h1>Inicio de sesión</h1>";
    ?>
    <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
    <fieldset>
        <legend>Introduce los datos de inicio de sesión:</legend>
        <label for="email">Email</label>
        <input type="email" name="email" id="email" required>
        <label for="clave">Clave</label>
        <input type="password" name="clave" id="clave" required>
    </fieldset>
    <input type="submit" name="operacion" id="operacion" value="Iniciar sesión">
    </form>
    <?php
} else if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    // Saneamos los datos
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['clave'];

    // Validamos los datos necesarios
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);

    try {
    // // Creamos la conexión con PDO
    // $dsn = "mysql:host=mysql;dbname=tiendaol;charset=utf8mb4";
    // $usuario = "usuario";
    // $clave = "usuario";
    // $opciones = [
    //     PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    //     PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    //     PDO::ATTR_EMULATE_PREPARES => FALSE
    // ];
    // // Creamos nuestro pdo
    // $pdo = new PDO($dsn, $usuario, $clave, $opciones);

    // // Generamos la consulta
    // $sql = "SELECT nif, nombre, apellidos, clave, iban, telefono, email, ventas FROM cliente WHERE email = :email";

    // // Preparamos la consulta
    // $stmt = $pdo->prepare($sql);
    // $stmt->bindValue(':email', $email);

    // Creamos la conexión con mysqli
    $cbd = new mysqli("mysql", "usuario", "usuario", "tiendaol", 3306);

    // Preparamos la consulta
    $sql = "SELECT nif, nombre, apellidos, clave, iban, telefono, email, ventas";
    $sql.= " FROM cliente";
    $sql.= " WHERE email = ?";
    $stmt = $cbd->prepare($sql);

    $stmt->bind_param('s', $email);
    $stmt->execute();
    $resultset = $stmt->get_result();

    if ($resultset->num_rows == 1){
        $usuario = $resultset->fetch_assoc();
            if (password_verify($password, $usuario['clave'])){
                $payload = [
                    'nif' => $usuario['nif'],
                    'email' => $usuario['email'],
                    'nombre' => $usuario['nombre'] . " " . $usuario['apellidos']
                ];
                $jwt = generar_token($payload);
                setcookie("jwt", $jwt, time() + 20 * 60);

                header("Location: pantalla_articulos.php");
            }else{
                echo "La clave no es correcta";
            }
        }
    }catch(Exception $e){
        echo "Mensaje de error: " . $e->getMessage();
    }
}

ob_flush();
?>