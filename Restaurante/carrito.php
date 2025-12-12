<?php
    //Activamos la sesión
    session_start();

    //Importamos las funciones de la base de datos
    require_once 'bd.php';

    //Nos aseguramos que exista el carrito en la sesión
    if(!isset($_SESSION["carrito"])){
        $_SESSION["carrito"]= [];
    }
    $carrito= $_SESSION["carrito"];

    //Creamos un array con la info completa de cada línea del carrito
    $lineasCarrito= [];
    $pesoTotal= 0.0;

    //Recorremos el carrito
    foreach($carrito as $codProd => $unidades){
        //Obtenemos los datos del producto
        $producto= obtenerProductoPorCodigo((int)$codProd);

        //Si el producto ya no existe en la base de datos, lo saltamos
        if(!$producto){
            continue;
        }

        //Peso unitario (por defecto 0 si viene null)
        $pesoUnitario= isset($producto["peso"]) ? (float)$producto["peso"] : 0;
        $pesoLinea= $pesoUnitario * $unidades;
        $pesoTotal += $pesoLinea;

        $lineasCarrito[]= [
            "codProd" => $codProd,
            "nombre" => $producto["nombre"],
            "descripcion" => $producto["descripcion"],
            "pesoUnitario" => $pesoUnitario,
            "unidades" => $unidades,
            "pesoLinea" => $pesoLinea
        ];
    }//Fin foreach

    //Guardamos el peso total en la sesión para usarlo en procesar_pedido.php
    $_SESSION["peso_total"]= $pesoTotal;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de la compra</title>
</head>
<body>
    <h1>Carrito de la compra</h1>
    
    <p><a href="categorias.php">Volver a categorías</a></p>

    <?php if(empty($lineasCarrito)){ ?>
        <p>Tu carrito está vacío.</p>
    <?php }else{ ?>
        <table border="1">
            <tr>
                <th>Producto</th>
                <th>Descripción</th>
                <th>Peso unitario (kg)</th>
                <th>Unidades</th>
                <th>Peso total (kg)</th>
                <th>Acciones</th>
            </tr>

            <?php foreach($lineasCarrito as $linea){ ?>
                <tr>
                    <td><?= htmlspecialchars($linea['nombre']) ?></td>
                    <td><?= htmlspecialchars($linea['descripcion']) ?></td>
                    <td><?= htmlspecialchars(number_format($linea['pesoUnitario'], 2, ',', '.')) ?></td>
                    <td><?= htmlspecialchars($linea['unidades']) ?></td>
                    <td><?= htmlspecialchars(number_format($linea['pesoLinea'], 2, ',', '.')) ?></td>
                    <td>
                        <!--Formulario para eliminar unidades de este producto-->
                        <form action="eliminar.php" method="post" style="display:inline;">
                            <input type="hidden" name="cod" value="<?= htmlspecialchars($linea['codProd']) ?>">
                            <input type="number" name="unidades" value="1" min="1" max="<?= htmlspecialchars($linea['unidades']) ?>">
                            <input type="submit" value="Eliminar">
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </table>

        <br>

        <p>Peso total del pedido:
            <?= htmlspecialchars(number_format($pesoTotal, 2, ',', '.')) ?> Kg
        </p>

        <!--Formulario para confirmar el pedido-->
        <form action="procesar_pedido.php" method="post">
            <input type="submit" value="Confirmar pedido">
        </form>
    <?php } ?>
</body>
</html>