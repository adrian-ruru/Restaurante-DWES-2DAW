<?php
    //Activamos la sesión
    session_start();

    //Importamos las funciones de 'sesiones.php'
    require_once 'sesiones.php';
    comprobar_sesion();

    //Importamos las funciones de la base de datos
    require_once 'bd.php';

    //Comprobamos que nos han pasado una categoría por GET
    if(!isset($_GET["categoria"]) || !is_numeric($_GET["categoria"])){
        //Si no hay categoría, redirigimos a categorias.php
        header("Location: categorias.php");
        exit;
    }

    $codCat= (int)$_GET["categoria"];

    //Obtenemos los productos de esa categoría
    $productos= obtenerProductosPorCategoria($codCat);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="page-body">
    <?php include 'cabecera.php'; ?>

    <main class="content-container">
        <h1 class="page-title">Productos de la categoría <?= htmlspecialchars($codCat) ?></h1>

        <p class="back-link">
            <a class="button-link" href="categorias.php">Volver a categorías</a> |
            <a class="button-link" href="carrito.php">Ver carrito</a>
        </p>

        <?php if(empty($productos)){ ?>
            <p class="empty-message">No hay productos en esta categoria.</p>
        <?php }else { ?>
            <table class="styled-table">
                <tr class="table-row">
                    <th class="table-header">Nombre</th>
                    <th class="table-header">Descripción</th>
                    <th class="table-header">Peso</th>
                    <th class="table-header">Stock</th>
                    <th class="table-header">Añadir al carrito</th>
                </tr>

                <?php foreach($productos as $producto){ ?>
                    <tr class="table-row">
                        <td class="table-cell"><?= htmlspecialchars($producto['nombre']) ?></td>
                        <td class="table-cell"><?= htmlspecialchars($producto['descripcion']) ?></td>
                        <td class="table-cell"><?= htmlspecialchars($producto['peso']) ?> kg</td>
                        <td class="table-cell"><?= htmlspecialchars($producto['stock']) ?></td>
                        <td class="table-cell">
                            <form class="inline-form" action="anadir.php" method="post">
                                <!--Código del producto-->
                                <input type="hidden" name="cod" value="<?= htmlspecialchars($producto['codProd']) ?>">
                                <!--Unidades a añadir-->
                                <input class="number-input" type="number" name="unidades" value="1" min="1">
                                <input class="primary-button" type="submit" value="Añadir">
                            </form>
                        </td>
                    </tr>
               <?php } ?>
            </table>
       <?php } ?>
    </main>
</body>
</html>
