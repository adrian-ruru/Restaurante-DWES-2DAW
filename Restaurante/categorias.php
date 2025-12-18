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
    <link rel="stylesheet" href="styles.css">
</head>
<body class="page-body">
    <?php include 'cabecera.php'; ?>

    <main class="content-container">
        <h1 class="page-title">Categorías</h1>

        <?php if(empty($categorias)){ ?>
            <p class="empty-message">No hay categorías disponibles.</p>
        <?php }else{ ?>
            <ul class="category-list">
                <?php foreach($categorias as $cat) { ?>
                    <li class="category-item">
                        <a class="category-link" href="productos.php?categoria=<?= htmlspecialchars($cat['codCat']) ?>">
                             <?= htmlspecialchars($cat['nombre']) ?>
                        </a>
                        <?php if(!empty($cat['descripcion'])) { ?>
                            <span class="category-description">- <?= htmlspecialchars($cat['descripcion']) ?></span>
                        <?php } ?>
                    </li>
                <?php } ?>
            </ul>
        <?php } ?>

        <p class="back-link"><a class="button-link" href="carrito.php">Ver carrito</a></p>
    </main>
</body>
</html>
