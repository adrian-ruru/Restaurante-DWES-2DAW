<?php
    //Activamos la sesión
    session_start();

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
</head>
<body>
    <h1>Productos de la categoría <?= htmlspecialchars($codCat) ?></h1>

    <p><a href="categorias.php">Volver a categorías</a> | <a href="carrito.php">Ver carrito</a></p>

    <?php if(empty($productos)){ ?>
        <p>No hay productos en esta categoria.</p>
    <?php }else { ?>
        <table border="1">
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Peso</th>
                <th>Stock</th>
                <th>Añadir al carrito</th>
            </tr>

            <?php foreach($productos as $producto){ ?>
                <tr>
                    <td><?= htmlspecialchars($producto['nombre']) ?></td>
                    <td><?= htmlspecialchars($producto['descripcion']) ?></td>
                    <td><?= htmlspecialchars($producto['peso']) ?> kg</td>
                    <td><?= htmlspecialchars($producto['stock']) ?></td>
                    <td>
                        <form action="anadir.php" method="post">
                            <!--Código del producto-->
                            <input type="hidden" name="cod" value="<?= htmlspecialchars($producto['codProd']) ?>">
                            <!--Unidades a añadir-->
                            <input type="number" name="unidades" value="1" min="1">
                            <input type="submit" value="Añadir">
                        </form>
                    </td>
                </tr>
           <?php } ?>
        </table>
   <?php } ?>
</body>
</html>