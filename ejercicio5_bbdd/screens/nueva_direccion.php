<?php
// Iniciamos sesion
session_start();

// OB_START
ob_start();

// Recogemos los datos que nos hagan falta
require_once($_SERVER['DOCUMENT_ROOT'] . "/ejercicios_clases_interfaces/includes/funciones.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/ejercicios_clases_interfaces/includes/03jwt_include.php");

// Validación JWT
if ($_COOKIE['jwt']){
    $jwt = $_COOKIE['jwt'];
    $payload = verificar_token($jwt);
}

// Iniciamos HTML
inicio_html("Añadir nueva dirección", ['../../styles/general.css', '../../styles/formulario.css']);

// Comprobación de petición
if ($_SERVER['REQUEST_METHOD'] == 'GET' && $payload){
    echo "<h1>Añadir nueva dirección</h1>";
    ?>
    <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
        <fieldset>
            <legend>Introduce los datos para ingresar una nueva dirección:</legend>
            <input type="text" name="nif" id="nif" value="<?=$payload['nif']?>" hidden>
            <label for="id_dir_env">ID_DIR_ENV</label>
            <input type="number" name='id_dir_env' id="id_dir_env" required>
            <label for="direccion">Direccion</label>
            <input type="text" name="direccion" id="direccion">
            <label for="codigo_postal">Código postal</label>
            <input type="text" name="codigo_postal" id="codigo_postal">
            <label for="poblacion">Población</label>
            <input type="text" name="poblacion" id="poblacion">
            <label for="provincia">Provincia</label>
            <input type="text" name="provincia" id="provincia">
            <label for="pais">País</label>
            <input type="text" name="pais" id="pais">
        </fieldset>
        <input type="submit" name="operacion" id="operacion">
    </form>
    <?php
}else if ($_SERVER['REQUEST_METHOD'] == 'POST' && $payload){
    // Array de saneamiento de los datos
    $array_saneamiento = [
                        'nif' => FILTER_SANITIZE_SPECIAL_CHARS,
                        'id_dir_env' => FILTER_SANITIZE_NUMBER_INT,
                        'direccion' => FILTER_SANITIZE_SPECIAL_CHARS,
                        'codigo_postal' => FILTER_SANITIZE_SPECIAL_CHARS,
                        'poblacion' => FILTER_SANITIZE_SPECIAL_CHARS,
                        'provincia' => FILTER_SANITIZE_SPECIAL_CHARS,
                        'pais' => FILTER_SANITIZE_SPECIAL_CHARS
    ];

    // Saneamos los datos
    $datos_saneados = filter_input_array(INPUT_POST, $array_saneamiento);

    // Validación de los datos necesarios
    $datos_saneados['id_dir_env'] = filter_var($datos_saneados['id_dir_env'], FILTER_VALIDATE_INT,[
                                                                                'options' => ['min_range' => 0]
                                                                              ]);
    
    // Conexión a la base de datos
    try{
        // Creamos la conexión
        $cbd = new mysqli("mysql", "usuario", "usuario", "tiendaol", 3306);

        // Genero la consulta sql
        $sql = "INSERT INTO direccion_envio";
        $sql.= " VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $cbd->prepare($sql);

        // Almacenamos los valores del array
        $datos = array_values($datos_saneados);

        // Vinculamos los datos
        $stmt->bind_param('sisssss', ...$datos);

        // Ejecutamos la consulta
        if ($stmt->execute() && $stmt->affected_rows == 1){
            echo "<h2>Se ha introducido correctamente la dirección</h2>";
            echo "<a href='pantalla_listado_direcciones.php'>Volver a las direcciones</a>";
        }
    }catch(Exception $e){
        echo "Mensaje error: " . $e->getMessage();
    }
}

// ob_flush
ob_flush();
?>