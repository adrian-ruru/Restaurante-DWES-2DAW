<?php
    //Activamos la sesión
    session_start();

    //Importamos las funciones de 'sesiones.php'
    require_once 'sesiones.php';
    comprobar_sesion();

    //Importamos las funciones de la base de datos
    require_once 'bd.php';

    //Importamos las funciones de correo
    require_once 'correo.php';

    //Comprobamos que la petición viene por POST
    if($_SERVER["REQUEST_METHOD"] !== "POST"){
        header("Location: carrito.php");
        exit;
    }

    //Comprobamos que el carrito exista y no esté vacío
    if(!isset($_SESSION["carrito"]) || empty($_SESSION["carrito"])){
        $mensaje= "No hay ningún producto en el carrito. No se puede procesar el pedido.";
        $exito= false;
    }else{
        $carrito= $_SESSION["carrito"];

        //Obtenemos el peso total desde la sesión
        $pesoTotal= $_SESSION["peso_total"] ?? 0;

        //Por seguridad, si el peso es <= 0, lo recalculamos
        if($pesoTotal <= 0){
            $pesoTotal= 0.0;

            foreach($carrito as $codProd => $unidades){
                $producto= obtenerProductoPorCodigo((int)$codProd);

                if(!$producto){
                    continue;
                }

                //Peso unitario (por defecto 0 si viene null)
                $pesoUnitario= isset($producto["peso"]) ? (float)$producto["peso"] : 0;
                $pesoLinea= $pesoUnitario * $unidades;
                $pesoTotal += $pesoLinea;
            }//Fin foreach
        }

        //Comprobamos que la sesión contiene el código del restaurante que realiza el pedido
        if(!isset($_SESSION["codRes"])){
            $mensaje= "No hay ningún restaurante asociado a la sesión. Debes iniciar sesión.";
            $exito= false;
        }else{
            $codRestaurante= (int)$_SESSION["codRes"];

            try{
                //Insertamos el pedido en la base de datos
                $codPedido= insertarPedido($codRestaurante, $pesoTotal, $carrito);

                //Enviamos correos al restaurante y al departamento de pedidos
                $ok1= enviarCorreoRestaurante($codPedido);
                //$ok2= enviarCorreoDepartamentoPedidos($codPedido);

                if($ok1 /*&& $ok2*/){
                    marcarPedidoComoEnviado($codPedido);

                    //Vaciamos el carrito y el peso total
                    unset($_SESSION["carrito"]);
                    unset($_SESSION["peso_total"]);

                    $mensaje= "Pedido realizado correctamente. Código de pedido: " . $codPedido;
                    $exito= true;

                    //Redirigimos al HTML generado
                    header("Location: " . obtenerUrlCorreoRestuarante($codPedido));
                    exit;
                }                        
            }catch(Exception $e){
                $mensaje= "Ha ocurrido un error al procesar el pedido: " . $e -> getMessage();
                $exito= false;
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procesar pedido</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="page-body">
    <?php include 'cabecera.php'; ?>

    <main class="content-container">
        <h1 class="page-title">Procesar pedido</h1>

        <p class="info-text"><?= htmlspecialchars($mensaje) ?></p>

        <?php if($exito){ ?>
            <p class="summary-text">Peso total del pedido: <strong><?= htmlspecialchars(number_format($pesoTotal, 2, ',', '.')) ?> Kg</strong></p>
        <?php } ?>

        <p class="back-link">
            <a class="button-link" href="categorias.php">Volver a categorías</a> |
            <a class="button-link" href="carrito.php">Ver carrito</a>
        </p>
    </main>
</body>
</html>