<?php
    //Activamos la sesión
    session_start();

    //Importamos las funciones de 'sesiones.php'
    require_once 'sesiones.php';
    comprobar_sesion();

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
    <link rel="stylesheet" href="styles.css">
</head>
<body class="page-body">
    <?php include 'cabecera.php'; ?>

    <main class="content-container">
        <h1 class="page-title">Carrito de la compra</h1>

        <p class="back-link"><a class="button-link" href="categorias.php">Volver a categorías</a></p>

        <?php if(empty($lineasCarrito)){ ?>
            <p class="empty-message">Tu carrito está vacío.</p>
        <?php }else{ ?>
            <table class="styled-table">
                <tr class="table-row">
                    <th class="table-header">Producto</th>
                    <th class="table-header">Descripción</th>
                    <th class="table-header">Peso unitario (kg)</th>
                    <th class="table-header">Unidades</th>
                    <th class="table-header">Peso total (kg)</th>
                    <th class="table-header">Acciones</th>
                </tr>

                <?php foreach($lineasCarrito as $linea){ ?>
                    <tr class="table-row">
                        <td class="table-cell"><?= htmlspecialchars($linea['nombre']) ?></td>
                        <td class="table-cell"><?= htmlspecialchars($linea['descripcion']) ?></td>
                        <td class="table-cell"><?= htmlspecialchars(number_format($linea['pesoUnitario'], 2, ',', '.')) ?></td>
                        <td class="table-cell"><?= htmlspecialchars($linea['unidades']) ?></td>
                        <td class="table-cell"><?= htmlspecialchars(number_format($linea['pesoLinea'], 2, ',', '.')) ?></td>
                        <td class="table-cell">
                            <!--Formulario para eliminar unidades de este producto-->
                            <form class="inline-form" action="eliminar.php" method="post">
                                <input type="hidden" name="cod" value="<?= htmlspecialchars($linea['codProd']) ?>">
                                <input class="number-input" type="number" name="unidades" value="1" min="1" max="<?= htmlspecialchars($linea['unidades']) ?>">
                                <input class="secondary-button" type="submit" value="Eliminar">
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </table>

            <p class="summary-text">Peso total del pedido:
                <strong><?= htmlspecialchars(number_format($pesoTotal, 2, ',', '.')) ?> Kg</strong>
            </p>

            <!--Formulario para confirmar el pedido-->
            <form class="confirm-form" action="procesar_pedido.php" method="post">
                <input class="primary-button" type="submit" value="Confirmar pedido">
            </form>
        <?php } ?>
    </main>
</body>
</html>
