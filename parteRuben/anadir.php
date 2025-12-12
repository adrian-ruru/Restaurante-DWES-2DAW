<?php
    //Activamos la sesión
    session_start();

    //Importamos las funciones de la base de datos
    require_once 'bd.php';

    //Comprobamos que la peticioón viene por POST
    if($_SERVER["REQUEST_METHOD"] !== "POST"){
        //Si no viene por POST, redirigimos a categorias.php
        header("Location: categorias.php");
        exit;
    }
    
    //Comprobamos que nos llegan los datos necesarios
    if(!isset($_POST["cod"]) || !isset($_POST["unidades"])){
        //Si faltan datos, redirigimos a categorias.php
        header("Location: categorias.php");
        exit;
    }

    //Recuperamos y validamos los datos
    $codProd= $_POST["cod"];
    $unidades= $_POST["unidades"];

    if(!is_numeric($codProd) || !is_numeric($unidades)){
        //Los datos no son válidos
        header("Location: categorias.php");        
        exit;
    }

    $codProd= (int)$codProd;
    $unidades= (int)$unidades;

    //Si las unidades son 0 o negativas, no añadimos
    if($unidades <= 0){
        header("Location: carrito.php");
        exit;
    }

    //Comprobamos que el producto existe en la base de datos
    $producto= obtenerProductoPorCodigo($codProd);

    if(!$producto){
        //No existe el producto
        header("Location: categorias.php");
        exit;
    }

    //Nos aseguramos que exista el carrito en la sesión
    if(!isset($_SESSION["carrito"])){
        $_SESSION["carrito"]= [];
    }

    //Si ya hay unidades de ese producto, sumamos. Si no, lo creamos
    if(isset($_SESSION["carrito"][$codProd])){
        $_SESSION["carrito"][$codProd] += $unidades;
    }else{
        $_SESSION["carrito"][$codProd]= $unidades;
    }

    //Redirigimos al carrito para ver el resultado
    header("Location: carrito.php");
    exit;
?>