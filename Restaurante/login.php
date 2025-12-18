<?php
    //Activamos la sesión
    session_start();

    //Importamos las funciones de la base de datos
    require_once 'bd.php';

    //Si ya hay sesión iniciada, redirigimos
    if(isset($_SESSION["codRes"])) {
        header("Location: categorias.php");
        exit;
    }

    $error= "";

    //Comprobamos si el formulario ha sido enviado
    if($_SERVER["REQUEST_METHOD"] === "POST") {
        //Recuperamos y limpiamos los datos del formulario
        $correo= trim($_POST["correo"] ?? "");
        $clave= trim($_POST["clave"] ?? "");

        //Validamos que los campos no estén vacíos
        if($correo === "" || $clave === "") {
            $error= "Debes rellenar todos los campos.";
        }else{
            //Buscamos el restaurante por correo
            $restaurante= obtenerRestaurantePorCorreo($correo);
            
            if($restaurante){
                //Comprobamos la contraseña
                if($clave === $restaurante["clave"]){
                    //Iniciamos sesión
                    $_SESSION["codRes"]= (int)$restaurante["codRes"];
                    $_SESSION["correo"]= $restaurante["correo"];

                    header("Location: categorias.php");
                    exit;
                }else{
                    $error= "Contraseña incorrecta.";
                }
            }else{
                $error= "No existe ningún restaurante con ese correo.";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
        <link rel="stylesheet" href="styles.css">
    </head>

    <body class="page-body">
        <main class="auth-container">
            <h1 class="page-title">Iniciar sesión</h1>

            <?php if($error !== "") { ?>
                <p class="error-text"><?= htmlspecialchars($error) ?></p>
            <?php } ?>

            <form class="auth-form" method="post">
                <label class="form-label">
                    Correo:
                    <input class="text-input" type="email" name="correo" required>
                </label>

                <label class="form-label">
                    Contraseña:
                    <input class="text-input" type="password" name="clave" required>
                </label>

                <input class="primary-button" type="submit" value="Entrar">
            </form>
        </main>
    </body>
</html>
