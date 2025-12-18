<?php
    //Activamos la sesión
    session_start();

    //Importamos las funciones de 'sesiones.php'
    require_once 'sesiones.php';
    comprobar_sesion();
    
    //Importamos las funciones de la base de datos
    require_once 'bd.php';    

    $categorias= obtenerCategorias();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorías</title>
</head>
<body>
    <?php include 'cabecera.php'; ?>
    
    <h1>Categorías</h1>

    <?php if(empty($categorias)){ ?>
        <p>No hay categorías disponibles.</p>
    <?php }else{ ?>
        <ul>
            <?php foreach($categorias as $cat) { ?>
                <li>
                    <a href="productos.php?categoria=<?= htmlspecialchars($cat['codCat']) ?>">
                         <?= htmlspecialchars($cat['nombre']) ?>
                    </a>
                    <?php if(!empty($cat['descripcion'])) { ?>
                        - <?= htmlspecialchars($cat['descripcion']) ?>
                    <?php } ?>
                </li>
            <?php } ?>
        </ul>
    <?php } ?>

    <p><a href="carrito.php">Ver carrito</a></p>
</body>
</html>