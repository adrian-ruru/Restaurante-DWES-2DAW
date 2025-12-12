<?php
    //1. Conectamos la base de datos
    const SERVIDOR= "localhost";
    const USUARIO= "root";
    const PASSWORD= "";
    const BASE_DATOS= "practicarestaurante";

    //Función para conectar la base de datos
    function conectarBD(): mysqli{
        $conexion= new mysqli(SERVIDOR, USUARIO, PASSWORD, BASE_DATOS);

        if($conexion -> connect_errno){
            die("Error de conexión: " . $conexion -> connect_error);
        }
        $conexion -> set_charset("utf8mb4");
        return $conexion;
    }//Fin función

    //Función para obtener las categorías
    function obtenerCategorias(): array{
        $conexion= conectarBD();

        $sql= "SELECT * FROM categorias ORDER BY nombre";
        $resultado= $conexion -> query($sql);

        $categorias= [];

        if($resultado){
            while($fila= $resultado -> fetch_assoc()){
                $categorias[]= $fila;
            }//Fin while
            $resultado -> free(); //Liberamos la memoria asociada al resultado de la consulta
        }
        
        //Cerramos la conexión
        $conexion -> close();
        return $categorias;
    }//Fin función

    //Función para obtener los productos de una categoría
    function obtenerProductosPorCategoria(int $codCat): array{
        $conexion= conectarBD();

        $sql= "SELECT codProd, nombre, descripcion, peso, stock FROM productos WHERE categoria= ?";

        $productos= [];

        if($stmt= $conexion -> prepare($sql)){
            $stmt -> bind_param("i", $codCat);
            $stmt -> execute();
            $resultado= $stmt -> get_result();

            while($fila= $resultado -> fetch_assoc()){
                $productos[]= $fila;
            }//Fin while
            $stmt -> close();
        }

        //Cerramos la conexión
        $conexion -> close();
        return $productos;
    }//Fin función

    //Función para obtener UN SOLO PRODUCTO por su código
    function obtenerProductoPorCodigo(int $codProd): ?array{
        $conexion= conectarBD();

        $sql= "SELECT * FROM productos WHERE codProd= ?";

        $producto= null;

        if($stmt= $conexion -> prepare($sql)){
            $stmt -> bind_param("i", $codProd);
            $stmt -> execute();
            $resultado= $stmt -> get_result();

            if($fila= $resultado -> fetch_assoc()){
                $producto= $fila;
            }
            $stmt -> close();
        }

        //Cerramos la conexión
        $conexion -> close();
        return $producto;
    }//Fin función

    //Función para insertar un pedido
    function insertarPedido(int $codRestaurante, float $pesoTotal, array $carrito): int{
        $conexion= conectarBD();
        $conexion -> begin_transaction(); //Realizamos la transacción de base de datos

        try{
            //Realizamos la inserción en la tabla 'pedidos'
            $sqlPedido= "INSERT INTO pedidos (fecha, enviado, peso, restaurante) VALUES (NOW(), 0, ?, ?)"; //enviado= 0 al crear el pedido
            $stmtPed= $conexion -> prepare($sqlPedido);
            $stmtPed -> bind_param("di", $pesoTotal, $codRestaurante);
            $stmtPed -> execute();

            $codPedido= $conexion -> insert_id; //Obtenemos el codPed que se acaba de insertar generado por la conexión
            $stmtPed -> close();

            //Insertamos las líneas en la tabla 'pedidosproductos'
            $sqlLinea= "INSERT INTO pedidosproductos (pedido, producto, unidades) VALUES (?, ?, ?)";
            $stmtLin= $conexion -> prepare($sqlLinea);

            //Recorremos el array del carrito
            foreach($carrito as $codProd => $unidades){
                $stmtLin -> bind_param("iii", $codPedido, $codProd, $unidades);
                $stmtLin -> execute();
            }//Fin foreach
            $stmtLin -> close();
            $conexion -> commit(); //Confirmamos la transacción iniciada con begin_transaction()

            //Cerramos la conexión
            $conexion -> close();
            return $codPedido;
        }catch(Exception $e){
            $conexion -> rollback(); //Deshacemos la transacción y revertimos todos los cambios hechos desde begin_transaction()
            $conexion -> close();
            throw $e;
        }
    }//Fin función
?>