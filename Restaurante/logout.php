<?php

    //Activamos y cerramos la sesión
    session_start();
    session_destroy();

    header("Location: login.php");
    exit;
?>