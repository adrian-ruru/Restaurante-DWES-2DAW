<?php
    //Importamos las funciones de la base de datos
    require_once 'bd.php';

    //Creamos una carpeta (con permisos de escritura) donde guardamos los correos generados
    const CARPETA_MAILS= __DIR__ . "/mails";

    //"Destinatario" lógico (en modo fichero solo se usan como etiqueta)
    const NOMBRE_RESTAURANTE_PROVEEDOR= "La Casa del Capricho";
    const CORREO_RESTAURANTE_PROVEEDOR= "lacasadelcapricho@empresa-restaurante.local";
    const CORREO_DEPTO_PEDIDOS= "pedidos@empresa-restaurante.local";

    //Función para construir el html del correo del pedido
    function construirHtmlCorreoPedido(array $pedido, array $lineas, string $destinatario): string{
        $codPed= (int)$pedido["codPed"];
        $correoRest= $pedido["correoRestaurante"] ?? "-"; //Restaurante que hizo el pedido (BD)
        $fecha= $pedido["fecha"] ?? "-";
        $peso= isset($pedido["peso"]) ? (float)$pedido["peso"] : 0.0;

        $codPedHtml= htmlspecialchars((string)$codPed);
        $destinatarioHtml= htmlspecialchars($destinatario);    
        $fechaHtml= htmlspecialchars((string)$fecha);
        $pesoHtml= htmlspecialchars(number_format($peso, 2, ",", "."));
        $proveedorCorreoHtml= htmlspecialchars(CORREO_RESTAURANTE_PROVEEDOR);
        $deptoHtml= htmlspecialchars(CORREO_DEPTO_PEDIDOS);

        $lineasHtml= "";

        foreach($lineas as $l){
            $nombre= $l["nombre"] ?? "";
            $descripcion= $l["descripcion"] ?? "";
            $pesoProd= isset($l["peso"]) ? (float)$l["peso"] : 0.0;
            $unidades= isset($l["unidades"]) ? (int)$l["unidades"] : 0;

            $pesoProdHtml= htmlspecialchars(number_format($pesoProd, 2, ",", "."));
            $unidadesHtml= htmlspecialchars((string)$unidades);
            $nombreHtml= htmlspecialchars($nombre);
            $descripcionHtml= htmlspecialchars($descripcion);

            $lineasHtml .= "<tr class=\"table-row\">";
            $lineasHtml .= "<td class=\"table-cell\">{$nombreHtml}</td>";
            $lineasHtml .= "<td class=\"table-cell\">{$descripcionHtml}</td>";
            $lineasHtml .= "<td class=\"table-cell\">{$pesoProdHtml}</td>";
            $lineasHtml .= "<td class=\"table-cell\">{$unidadesHtml}</td>";
            $lineasHtml .= "</tr>";
        }//Fin foreach

        $html= <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido nº {$codPedHtml}</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body class="page-body">
    <main class="content-container">
        <h1 class="page-title">Pedido nº {$codPedHtml}</h1>

        <div class="summary-text">
            <p><strong>Para:</strong> {$destinatarioHtml}</p>
            <p><strong>Restaurante:</strong> {$proveedorCorreoHtml}</p>
            <p><strong>Departamento de pedidos:</strong> {$deptoHtml}</p>
            <p><strong>Fecha:</strong> {$fechaHtml}</p>
            <p><strong>Peso total:</strong> {$pesoHtml} kg</p>
        </div>

        <h2 class="page-title">Detalle del pedido</h2>

        <table class="styled-table">
            <tr class="table-row">
                <th class="table-header">Nombre</th>
                <th class="table-header">Descripción</th>
                <th class="table-header">Peso (kg)</th>
                <th class="table-header">Unidades</th>
            </tr>
            {$lineasHtml}
        </table>

        <p class="info-text"><em>Mensaje generado automáticamente por la aplicación.</em></p>

        <div class="back-link">
            <a class="button-link" href="../categorias.php">Volver a categorías</a>
            <a class="button-link" href="../carrito.php">Ver carrito</a>
        </div>
    </main>
</body>
</html>
HTML;

        return $html;
    }//Fin función

    //Función para guardar los correos en un fichero
    function guardarCorreoEnFichero(string $nombreFichero, string $html): bool{
        if(!is_dir(CARPETA_MAILS)){
            //Intentamos crear la carpeta si no existe
            if(!mkdir(CARPETA_MAILS, 0755, true) && !is_dir(CARPETA_MAILS)){
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

    //Función que devuelve la ruta WEB (URL relativa) del HTML generado del restaurante
    function obtenerUrlCorreoRestuarante(int $codPedido): string{
        return "mails/pedido_{$codPedido}_restaurante.html";
    }//Fin función
?>