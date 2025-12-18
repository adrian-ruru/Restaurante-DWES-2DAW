<?php
    //Importamos las funciones de la base de datos
    require_once 'bd.php';

    //Creamos una carpeta (con permisos de escritura) donde guardamos los correos generados
    const CARPETA_MAILS= __DIR__ . "/mails";

    //"Destinatario" lógico (en modo fichero solo se usan como etiqueta)
    const CORREO_DEPTO_PEDIDOS= "pedidos@empresa-restaurante.local";

    //Función para construir el html del correo del pedido
    function construirHtmlCorreoPedido(array $pedido, array $lineas, string $destinatario): string{
        $codPed= (int)$pedido["codPed"];
        $correoRest= $pedido["correoRestaurante"] ?? "-";
        $fecha= $pedido["fecha"] ?? "-";
        $peso= isset($pedido["peso"]) ? (float)$pedido["peso"] : 0.0;

        //Creamos el html
        $html= "<h2>Pedido nº " . htmlspecialchars((string)$codPed) . "</h2>";
        $html .= "<p><strong>Para:</strong> " . htmlspecialchars($destinatario) . "</p>";
        $html .= "<p><strong>Restaurante:</strong> " . htmlspecialchars((string)$correoRest) . "</p>";
        $html .= "<p><strong>Fecha:</strong> " . htmlspecialchars((string)$fecha) . "</p>";
        $html .= "<p><strong>Peso total:</strong> " . htmlspecialchars(number_format($peso, 2, ",", ".")) . " kg</p>";

        $html .= "<h3>Detalle del pedido</h3>";
        $html .= "<table border='1' cellpadding='6' cellspacing='0'>";
        $html .= "<tr><th>Nombre</th><th>Descripción</th><th>Peso (kg)</th><th>Unidades</th></tr>";

        foreach($lineas as $l){
            $nombre= $l["nombre"] ?? "";
            $descripcion= $l["descripcion"] ?? "";
            $pesoProd= isset($l["peso"]) ? (float)$l["peso"] : 0.0;
            $unidades= isset($l["unidades"]) ? (int)$l["unidades"] : 0;

            $html .= "<tr>";
            $html .= "<td>" . htmlspecialchars($nombre) . "</td>";
            $html .= "<td>" . htmlspecialchars($descripcion) . "</td>";
            $html .= "<td style='text-align:right;'>" . htmlspecialchars(number_format($pesoProd, 2, ",", ".")) . "</td>";
            $html .= "<td style='text-align:right;'>" . htmlspecialchars((string)$unidades) . "</td>";
            $html .= "</tr>";
        }//Fin foreach

        $html .= "</table>";
        $html .= "<p><em>Mensaje generado automáticamente por la aplicación.</em></p>";

        return "<!DOCTYPE html><html><head><meta charset='UTF-8'></head><body>$html</body></html>";
    }//Fin función

    //Función para guardar los correos en un fichero
    function guardarCorreoEnFichero(string $nombreFichero, string $html): bool{
        if(!is_dir(CARPETA_MAILS)){
            //Intentamos crear la carpeta si no existe
<<<<<<< HEAD
            if(!mkdir(CARPETA_MAILS, 0755, true) && !is_dir(CARPETA_MAILS)){
=======
            if(!mkdir(CARPETA_MAILS, 0777, true) && !is_dir(CARPETA_MAILS)){
>>>>>>> b11d05e18f546a057833a676d6be6386b4b9d0c6
                return false;
            }
        }

        $ruta= CARPETA_MAILS . "/" . $nombreFichero;
        return file_put_contents($ruta, $html) !== false;
    }//Fin función

    //Función para enviar los correos al restaurante
    function enviarCorreoRestaurante(int $codPedido): bool{
        $pedido= obtenerPedidoParaCorreo($codPedido);

        if(!$pedido){
            return false;
        }

        $lineas= obtenerLineasPedidoParaCorreo($codPedido);
        $destinatario= $pedido["correoRestaurante"] ?? "-";

        $html= construirHtmlCorreoPedido($pedido, $lineas, $destinatario);
        return guardarCorreoEnFichero("pedido_{$codPedido}_restaurante.html", $html);
    }//Fin función

    //Función para enviar los correos al departamento de pedidos
    function enviarCorreoDepartamentoPedidos(int $codPedido): bool{
        $pedido= obtenerPedidoParaCorreo($codPedido);

        if(!$pedido){
            return false;
        }

        $lineas= obtenerLineasPedidoParaCorreo($codPedido);
        $destinatario= CORREO_DEPTO_PEDIDOS;

        $html= construirHtmlCorreoPedido($pedido, $lineas, $destinatario);
        return guardarCorreoEnFichero("pedido_{$codPedido}_depto.html", $html);
    }//Fin función
?>