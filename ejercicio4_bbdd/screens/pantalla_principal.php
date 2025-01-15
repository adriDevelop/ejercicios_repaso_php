<?php
    session_start();
    ob_start();

    require_once($_SERVER['DOCUMENT_ROOT'] . "/ejercicios_clases_interfaces/includes/funciones.php");

    inicio_html("Pantalla principal", ['../../styles/general.css', '../../styles/tablas.css', '../../styles/formulario.css']);

    if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_SESSION['usuario'] && $_SESSION['clave']){
        ?>
            <h1>Búsqueda de artículos</h1>
            <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
            <fieldset>
                <legend>Cuadro de búsqueda</legend>
                <label for="referencia">Referencia</label>
                <input type="text" name="referencia" id="referencia">
                <label for="categoria">Categoria</label>
                <input type="text" name="categoria" id="categoria">
            </fieldset>
            <input type="submit" name="operacion" id="operacion" value="Buscar">
            <input type="submit" name="nuevo" id="nuevo" value="Nuevo articulo">
            </form>
            
        <?php
    } else if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['email'] && $_SESSION['clave']) {
        
        // Debemos de barajar las opciones que damos de eliminar y modificar
        $eliminar = filter_input(INPUT_POST, 'eliminar', FILTER_SANITIZE_SPECIAL_CHARS);
        $modificar = filter_input(INPUT_POST, 'modificar', FILTER_SANITIZE_SPECIAL_CHARS);
        $nuevo = filter_input(INPUT_POST, 'nuevo', FILTER_SANITIZE_SPECIAL_CHARS);

        // Validar datos introducidos
        $referencia = filter_input(INPUT_POST, 'referencia', FILTER_SANITIZE_SPECIAL_CHARS);
        $categoria = filter_input(INPUT_POST, 'categoria', FILTER_SANITIZE_SPECIAL_CHARS);
        
        if ($eliminar == 'eliminar'){
            try{
            //conectar con la BBDD
            $cbd = new mysqli("mysql", $_SESSION['usuario'], $_SESSION['clave'], 'tiendaol', 3306);
            
            //controlador
            $controlador = new mysqli_driver();
            $controlador->report_mode = MYSQLI_REPORT_STRICT || MYSQLI_REPORT_ERROR;
            
            // Generar consulta sql de eliminación
            $sql = "DELETE FROM articulo";
            $sql.= " WHERE referencia = ?";
            $smtp = $cbd->prepare($sql);

            // Vinculamos el tipo de la busqueda
            $tipos = 's';
            $smtp->bind_param($tipos, $_SESSION['referencia']);

            if ($smtp->execute() && $smtp->affected_rows == 1){
                echo "<h2>Se ha eliminado correctamente la referencia {$_SESSION['referencia']}</h2>";
                echo "<a href='pantalla_principal.php'>Volver a refrescar</a>";
            } else {
                echo "<h2>No se ha borrado la referencia {$_SESSION['referencia']}</h2>";
            }

            

        }catch (Exception $e){
            echo "Mensaje error {$e->getMessage()}";
        }

        } else if ($modificar == 'modificar'){

        } else if ($nuevo == 'nuevo'){
            header("Location: pantalla_nuevo_articulo.php");
        }
        $_SESSION['referencia'] = $referencia;
            try{

            //conectar con la BBDD
            $cbd = new mysqli("mysql", $_SESSION['usuario'], $_SESSION['clave'], 'tiendaol', 3306);
            
            //controlador
            $controlador = new mysqli_driver();
            $controlador->report_mode = MYSQLI_REPORT_STRICT || MYSQLI_REPORT_ERROR;
            //consulta para ver datos a lo bruto
            $sql = "SELECT referencia, descripcion, pvp, dto_venta, und_vendidas, fecha_disponible, categoria, tipo_iva ";
            $sql .= " FROM articulo";
            $sql .= " WHERE referencia = ? OR categoria = ?";
            
            $smtp = $cbd->prepare($sql);

            // Una vez generada la consulta preparada, vinculamos los tipos de nuestros datos introducidos
            $tipos = 'ss';
            $smtp->bind_param($tipos, $referencia, $categoria);
            
            // Ejecutamos la consulta
            $smtp->execute();

            // Obtenemos el resultado
            $resultset = $smtp->get_result();
            ?>
            <h1>Búsqueda de artículos</h1>
                <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
                <fieldset>
                    <legend>Cuadro de búsqueda</legend>
                    <label for="referencia">Referencia</label>
                    <input type="text" name="referencia" id="referencia">
                    <label for="categoria">Categoria</label>
                    <input type="text" name="categoria" id="categoria">
                </fieldset>
                <input type="submit" name="operacion" id="operacion" value="Buscar">
                </form>
            <?php
            echo "<table>
                        <thead>
                        <tr>
                        <th>referencia </th>
                        <th>descripcion </th>
                        <th>pvp </th>
                        <th>dto_vent </th>
                        <th>und_vendida </th>
                        <th>fecha_disponible </th>
                        <th>categoria</th>
                        <th>tipo_iva </th>
                        <th>Opciones</th>
                        </tr>
                        </thead>";
        
            echo "<tbody>";
        
            while ($fila = $resultset->fetch_assoc()) {
                echo "<tr>";
                echo "<td> {$fila['referencia']}</td>";
                echo "<td> {$fila['descripcion']}</td>";
                echo "<td> {$fila['pvp']}</td>";
                echo "<td> {$fila['dto_venta']}</td>";
                echo "<td> {$fila['und_vendidas']}</td>";
                echo "<td> {$fila['fecha_disponible']}</td>";
                echo "<td> {$fila['categoria']}</td>";
                echo "<td> {$fila['tipo_iva']}</td>";
                echo "<td>
                        <form action='{$_SERVER['PHP_SELF']}' method='POST'>
                        <input type='submit' value='eliminar' name='eliminar'>
                        <input type='submit' value='modificar' name='modificar'>
                        </form>
                    </td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
            echo "<br>";
            echo "<form action='{$_SERVER['PHP_SELF']}' method='POST'>";
            echo "<input type='submit' name='nuevo' id='nuevo' value='nuevo'>";
            echo "</form>";
        }catch (Exception $e){
            echo "Código de error: {$e->getCode()}\nMensaje de error: {$e->getMessage()}";
        }
    }
    ob_flush();
?>