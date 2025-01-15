<?php
// Iniciamos sesión
session_start();

// Inicio ob
ob_start();

// Importamos datos necesarios
require_once($_SERVER['DOCUMENT_ROOT'] . "/ejercicios_clases_interfaces/includes/funciones.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/ejercicios_clases_interfaces/includes/03jwt_include.php");

// Validar JWT
if ($_COOKIE['jwt']){
    $jwt = $_COOKIE['jwt'];
    $payload = verificar_token($jwt);
}

// Inicio HTML
inicio_html("Pantalla direcciones", ['../../styles/general.css', '../../styles/tablas.css']);
if ($_SERVER['REQUEST_METHOD'] == 'GET' && $payload){
    echo "<h2>Bienvenido a la ventana listado direcciones</h2>";
    try{
    // Iniciamos conexión 
    $cbd = new mysqli("mysql", "usuario", "usuario", "tiendaol", 3306);

    // Preparo sql
    $sql = "SELECT nif, id_dir_env, direccion, cp, poblacion, provincia, pais";
    $sql.= " FROM direccion_envio";
    $sql.= " WHERE nif = ?";
    $stmt = $cbd->prepare($sql);

    // Enlazamos parametros
    $stmt->bind_param("s", $payload['nif']);

    // Ejecutamos la consulta
    $stmt->execute();

    // Almacenamos el resultado de la consulta
    $resultset = $stmt->get_result();

    // Mostramos los datos en una tabla
    echo "<table><thead><tr><th>nif</th><th>id_dire_env</th><th>direccion</th><th>Codigo postal</th><th>poblacion</th><th>provincia</th><th>pais</th><th>Eliminar</th><th>Editar</th></tr></thead>";
    echo "<tbody>";
    while ($fila = $resultset->fetch_assoc()){
        echo "<tr>";
        echo "<td>{$fila['nif']}</td>";
        echo "<td>{$fila['id_dir_env']}</td>";
        echo "<td>{$fila['direccion']}</td>";
        echo "<td>{$fila['cp']}</td>";
        echo "<td>{$fila['poblacion']}</td>";
        echo "<td>{$fila['provincia']}</td>";
        echo "<td>{$fila['pais']}</td>";
        ?>
        <td>
            <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
                <button type='submit' name='eliminar' id='eliminar' value="<?=$fila['id_dir_env']?>">Eliminar</button>
            </form>
        </td>
        <td>
            <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
                <button type='submit' name='operacion' id='operacion' value="<?=$fila['id_dir_env']?>">Editar</button>
            </form>
        </td>
        <?php
        echo "</tr>";
    }
    echo "</tbody></table>";
    }catch(Exception $e){
        echo "Mensaje: " . $e->getMessage();
    }
    ?>
    <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
        <input type='submit' name='operacion' id='operacion' value='Nuevo'>
    </form>
    <?php
} else if ($_SERVER['REQUEST_METHOD'] == 'POST' && $payload){

    // Validar el dato
    $operacion = filter_input(INPUT_POST, 'operacion', FILTER_SANITIZE_SPECIAL_CHARS);
    $eliminar = filter_input(INPUT_POST, 'eliminar', FILTER_SANITIZE_NUMBER_INT);
    $editar = filter_input(INPUT_POST, 'editar', FILTER_SANITIZE_NUMBER_INT);

    if ($operacion == 'Nuevo'){
        header("Location: nueva_direccion.php");
    }else if ($eliminar){
        try{
        
        // Creamos conexión con la base de datos
        $cbd = new mysqli("mysql", "usuario", "usuario", "tiendaol", 3306);

        // Preparamos consulta
        $sql = "DELETE FROM direccion_envio";
        $sql.= " WHERE nif = ? AND id_dir_env = ?";
        $stmt = $cbd->prepare($sql);

        // Vinculamos los parametros
        $stmt->bind_param("si", $payload['nif'], $eliminar);

        // Ejecutamos la consulta
        if ($stmt->execute() && $stmt->affected_rows == 1){
            echo "<h2>Se ha eliminado la dirección correctamente</h2>";
            echo "<a href='pantalla_listado_direcciones.php'>Volver al listado</a>";
        }
    }catch(Exception $e){
        echo "Mensaje error: " . $e->getMessage();
    }
    }else if ($editar){
        $_SESSION['id_dir_env'] = $editar;
        header("Location: nueva_direccion.php");
    }
}

// Elimino datos ob
ob_flush();
?>