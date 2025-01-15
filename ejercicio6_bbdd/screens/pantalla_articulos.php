<?php
// Inicio ob
ob_start();

// Importamos los datos necesarios
require_once($_SERVER['DOCUMENT_ROOT'] . "/ejercicios_clases_interfaces/includes/funciones.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/ejercicios_clases_interfaces/includes/03jwt_include.php");

// Comprobamos que el inicio de sesión haya sido correcto
if ($_COOKIE['jwt']){
    $jwt = $_COOKIE['jwt'];
    $payload = verificar_token($jwt);
}

// Iniciamos HTML
inicio_html("Pantalla articulos", ['../../styles/general.css', '../../styles/tablas.css']);

// Comprobamos la petición que hace el usuario
if ($_SERVER['REQUEST_METHOD'] == 'GET' && $payload){
    echo "<h1>Listado de artículos</h1>";
    
    // Comenzamos con la instanciación de nuestro PDO
    try{
        // Creamos nuestra conexión a la base de datos
        $dsn = "mysql:host=mysql;dbname=tiendaol;charset:utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ];
        // Creamos nuestro PDO
        $pdo = new PDO($dsn, "usuario", "usuario", $options);

        // Una vez creada preparamos la consulta
        $sql = "SELECT npedido, nif, fecha, observaciones, total_pedido FROM pedido WHERE nif = :nif";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nif', $payload['nif']);

        if ($stmt->execute()){
            echo "<table><thead><tr><th>npedido</th><th>nif</th><th>fecha</th><th>observaciones</th><th>total_pedido</th></tr></thead><tbody>";
            while ($datos = $stmt->fetch()){
                echo "<tr>";
                echo "<td>{$datos['npedido']}</td>";
                echo "<td>{$datos['nif']}</td>";
                echo "<td>{$datos['fecha']}</td>";
                echo "<td>{$datos['observaciones']}</td>";
                echo "<td>{$datos['total_pedido']}</td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        }
    }catch(PDOException $pdoe){
        echo "Mensaje de error: " . $pdoe->getMessage();
    }
}
?>