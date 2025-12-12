<?php
    //Activamos la sesión
    session_start();

    //Comprobamos que la petición viene por POST
    if($_SERVER["REQUEST_METHOD"] !== "POST"){
        header("Location: carrito.php");
        exit;
    }

    //Comprobamos que nos llegan los datos necesarios
    if(!isset($_POST["cod"]) || !isset($_POST["unidades"])){
        header("Location: carrito.php");
        exit;
    }

    //Recuperamos y validamos los datos
    $codProd= $_POST["cod"];
    $unidades= $_POST["unidades"];

    if(!is_numeric($codProd) || !is_numeric($unidades)){
        header("Location: carrito.php");
        exit;
    }

    $codProd= (int)$codProd;
    $unidades= (int)$unidades;

    //Si las unidades son 0 o negativas, no hacemos nada
    if($unidades <= 0){
        header("Location: carrito.php");
        exit;
    }

    //Nos aseguramos que exista el carrito
    if(!isset($_SESSION["carrito"])){
        $_SESSION["carrito"]= [];
    }

    //Si el producto no está en el carrito, tampoco hacemos nada
    if(!isset($_SESSION["carrito"][$codProd])){
        header("Location: carrito.php");
        exit;
    }

    //Unidades actuales en el carrito
    $unidadesActuales= $_SESSION["carrito"][$codProd];

    //Si queremos eliminar tantas o más unidades de las que hay, borramos el producto
    if($unidades >= $unidadesActuales){
        unset($_SESSION["carrito"][$codProd]);
    }else{
        //En caso contrario, restamos
        $_SESSION["carrito"][$codProd] -= $unidades;
    }

    //Redirigimos de vuelta al carrito
    header("Location: carrito.php");
    exit;
?>