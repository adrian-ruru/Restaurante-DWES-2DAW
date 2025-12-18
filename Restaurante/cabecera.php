<?php
?>

<header class="site-header">
    <div class="header-top">
        <h1 class="site-title">La Casa del Capricho</h1>

        <nav class="main-nav">
            <div class="user-info">
                <?php if(isset($_SESSION["correo"])) { ?>
                    <span class="user-label">Usuario:</span>
                    <span class="user-email"><?= htmlspecialchars($_SESSION["correo"]) ?></span>
                <?php } ?>
            </div>

            <div class="nav-links">
                <a class="nav-link" href="categorias.php">Inicio</a>
                <a class="nav-link" href="carrito.php">Ver carrito</a>

                <?php if(isset($_SESSION["codRes"])) { ?>
                    <a class="nav-link" href="logout.php">Cerrar sesión</a>
                <?php }else{ ?>
                    <a class="nav-link" href="login.php">Iniciar sesión</a>
                <?php } ?>
            </div>
        </nav>
    </div>
    <hr class="divider">
</header>

