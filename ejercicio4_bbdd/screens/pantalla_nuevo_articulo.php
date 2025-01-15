<?php

// Iniciamos sesion
session_start();

// Recogemos archivos necesarios
require_once($_SERVER['DOCUMENT_ROOT'] . "/ejercicios_clases_interfaces/includes/funciones.php");

// Iniciamos HTML
inicio_html("Agregar nuevo artículo", ['../../styles/general.css', '../../styles/formulario.css']);

if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_SESSION['usuario'] && $_SESSION['clave']){
    echo "<h2>Crear nuevo articulo</h2>";
    ?>
    <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
        <fieldset>
            <legend>Introduce los datos del nuevo artículo</legend>
            <label for="referencia">Referencia</label>
            <input type="text" name="referencia" id="referencia">
            <label for="descripcion">Descripción</label>
            <input type="text" name="descripcion" id="descripcion">
            <label for="pvp">PVP</label>
            <input type="number" name="pvp" id="pvp">
            <label for="dto_venta">DTO_Venta</label>
            <input type="number" step="0.01" name="dto_venta" id="dto_venta">
            <label for="und_vendidas">UND_Vendidas</label>
            <input type="number" name="und_vendidas" id="und_vendidas">
            <label for="und_disponibles">UND_Disponibles</label>
            <input type="number" name="und_disponibles" id="und_disponibles">
            <label for="fecha_disponible">Fecha_disponible</label>
            <input type="date" name="fecha_disponible" id="fecha_disponible">
            <label for="categoria">categoria</label>
            <input type="text" name="categoria" id="categoria">
            <label for="tipo_via">Tipo_via</label>
            <input type="text" name="tipo_via" id="tipo_via">
        </fieldset>
        <input type="submit" name="operacion" id="operacion" value="Crear nuevo articulo">
    </form>
    <?php
} else if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['usuario'] && $_SESSION['clave']){

    // Saneación de datos
    $array_saneacion = [
                        "referencia" => FILTER_SANITIZE_SPECIAL_CHARS,
                        "descripcion" => FILTER_SANITIZE_SPECIAL_CHARS,
                        "pvp" => FILTER_SANITIZE_NUMBER_FLOAT,
                        "dto_venta" => FILTER_SANITIZE_NUMBER_INT,
                        "und_vendidas" => FILTER_SANITIZE_NUMBER_INT,
                        "und_disponibles" => FILTER_SANITIZE_NUMBER_INT,
                        "fecha_disponible" => FILTER_DEFAULT,
                        "categoria" => FILTER_SANITIZE_SPECIAL_CHARS,
                        "tipo_via" => FILTER_SANITIZE_SPECIAL_CHARS
    ];

    $datos_saneados = filter_input_array(INPUT_POST, $array_saneacion);

    // Validación de datos
    $datos_saneados['pvp'] = filter_var($datos_saneados['pvp'], FILTER_VALIDATE_FLOAT, 
                                                                ['options' => [
                                                                            "min_range" => 0]
                                                                ]);
    $datos_saneados['dto_venta'] = filter_var($datos_saneados['dto_venta'], FILTER_VALIDATE_FLOAT, 
                                                                            ["options" => [
                                                                            "max_range" => 100,
                                                                            "min_range" => 0]
                                                                ]);
    $datos_saneados['und_vendidas'] = filter_var($datos_saneados['dto_venta'], FILTER_VALIDATE_INT, 
                                                                            ["options" => [
                                                                                "min_range" => 0]
                                                                ]);
    $datos_saneados['und_disponibles'] = filter_var($datos_saneados['dto_venta'], FILTER_VALIDATE_INT, 
                                                                            ["options" => [
                                                                                "min_range" => 0]
                                                                ]);
    
    // Conexión con la base de datos
    try {
        // Creación de la conexión
        $cbd = new mysqli("mysql", $_SESSION['usuario'], $_SESSION['clave'], "tiendaol", 3306);

        // Creación de la consulta preparada
        $sql = "INSERT INTO articulo (referencia, descripcion, pvp, dto_venta, und_vendidas, und_disponibles, fecha_disponible, categoria, tipo_iva)";
        $sql.= " VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $cbd->prepare($sql);

        // Obtenemos los valores del array de los datos saneados
        $datos = array_values($datos_saneados);

        // Y los vinculamos con los valores que va a recibir nuestra consulta preparada
        $stmt->bind_param("ssddsssss", ...$datos);

        // Ejecutamos y comprobamos que todo ha ido correctamente
        if ($stmt->execute() && $stmt->affected_rows == 1){
            echo "<h2>Articulo añadido correctamente</h2>";
            echo "<a href='pantalla_principal.php'>Volver a filtrado</a>";
        } else {
            echo "<h2>No se ha agregado el artículo</h2>";
            echo "<a href='pantalla_principal.php'>Volver a filtrado</a>";
        }

    }catch(Exception $e){
        echo "Error code " . $e->getCode() . "\nMessage error " . $e->getMessage() . "<br>";
        foreach($datos_saneados as $datos){
            echo "$datos<br>";
        }
    }
}
?>