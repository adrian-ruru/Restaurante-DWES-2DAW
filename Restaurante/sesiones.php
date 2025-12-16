<?php

    //Inicia sesión si no está iniciada
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    //Función que comprueba si el restaurante ha iniciado sesión
    function comprobar_sesion(): void {
        if(!isset($_SESSION["codRes"])) {
            header("Location: login.php");
            exit;
        }
    }//Fin función
?>