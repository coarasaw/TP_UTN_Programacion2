<?php
namespace App\Controllers;
use App\Models\PedidoDetalle;
use App\Models\Menu;

use \Firebase\JWT\JWT;

class MenuMenosVendidoController {

    public function getAll ($request, $response, $args) {
        
        $headersEnvio = getallheaders();
        $fechainicio = $args['fechainicio'];
        $fechafin = $args['fechafin'];
        
        $rta = PedidoDetalle::from('pedido_detalles as p')
            ->where('p.created_at', '>=', $fechainicio)
            ->where('p.created_at', '<=', $fechafin)
            ->where('estado_pedido', '<>', 'cancelado')
            ->Join('menus','p.id_menu', '=' , 'menus.id_menu')
            ->select('menus.descripcion as Combo ',PedidoDetalle::raw('sum(p.cantidad) as Cantidad'))
            ->groupBy('p.id_menu')
            ->orderBy('Cantidad')
            ->take(1)
            ->get();

        $response->getBody()->write(json_encode($rta));
        return $response;
    }
}