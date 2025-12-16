<?php

    require_once 'sesiones.php';

?>

<header>

    <h1>Aplicación Web Restaurante</h1>

    <nav style="display:flex; justify-content:space-between; align-items:center;">
        
        <!-- Lado izquierdo -->
        <div>

            <?php if (isset($_SESSION["correo"])) { ?>

                <strong>Usuario:</strong>
                <?= htmlspecialchars($_SESSION["correo"]) ?>

            <?php } ?>

        </div>

        <!-- Lado derecho -->
        <div>

            <a href="categorias.php">Home</a> |
            <a href="carrito.php">Ver carrito</a>

            <?php if (isset($_SESSION["codRes"])) { ?>

                | <a href="logout.php">Cerrar sesión</a>

            <?php } else { ?>

                | <a href="login.php">Iniciar sesión</a>
                
            <?php } ?>

        </div>

    </nav>

    <hr>

</header>