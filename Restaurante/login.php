<?php

    session_start();
    require_once 'bd.php';

    // Si ya hay sesión iniciada, redirigimos
    if (isset($_SESSION["codRes"])) {

        header("Location: categorias.php");
        exit;

    }

    $error = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $correo = trim($_POST["correo"] ?? "");
        $clave  = trim($_POST["clave"] ?? "");

        if ($correo === "" || $clave === "") {

            $error = "Debes rellenar todos los campos.";

        } else {

            $conexion = conectarBD();

            $sql = "SELECT codRes, clave FROM restaurantes WHERE correo = ?";
            if ($stmt = $conexion->prepare($sql)) {

                $stmt->bind_param("s", $correo);
                $stmt->execute();
                $resultado = $stmt->get_result();

                if ($fila = $resultado->fetch_assoc()) {

                    // Comprobamos la contraseña
                    if (password_verify($clave, $fila["clave"]) || $clave === $fila["clave"]) {
                        // INICIAMOS SESIÓN
                        $_SESSION["codRes"] = $fila["codRes"];
                        $_SESSION["correo"] = $correo;

                        header("Location: categorias.php");
                        exit;
                    } else {
                        $error = "Contraseña incorrecta.";
                    }

                } else {

                    $error = "No existe ningún restaurante con ese correo.";

                }

                $stmt->close();

            }

            $conexion->close();

        }

    }

?>

<!DOCTYPE html>
<html lang="es">

    <head>

        <meta charset="UTF-8">
        <title>Login</title>

    </head>

    <body>

        <h1>Iniciar sesión</h1>

        <?php if ($error !== "") { ?>

            <p style="color:red"><?= htmlspecialchars($error) ?></p>

        <?php } ?>

        <form method="post">

            <label>

                Correo:
                <input type="email" name="correo" required>

            </label><br><br>

            <label>

                Contraseña:
                <input type="password" name="clave" required>
                
            </label><br><br>

            <input type="submit" value="Entrar">

        </form>

    </body>

</html>