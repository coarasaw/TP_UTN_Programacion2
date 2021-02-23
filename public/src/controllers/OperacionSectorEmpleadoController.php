<?php
namespace App\Controllers;
use App\Models\PedidoDetalle;
use App\Models\Menu;

use \Firebase\JWT\JWT;

class OperacionSectorEmpleadoController {

    public function getAll ($request, $response, $args) {
        
        $headersEnvio = getallheaders();
        $fechainicio = $args['fechainicio'];
        $fechafin = $args['fechafin'];

        $rta = PedidoDetalle::from('pedido_detalles as p')
            ->where('p.created_at', '>=', $fechainicio)
            ->where('p.created_at', '<=', $fechafin)
            ->Join('menus','p.id_menu', '=' , 'menus.id_menu')
            ->select('menus.id_sector','email',PedidoDetalle::raw('count(p.id_menu) as Cantidad'))
            ->groupBy('menus.id_sector','email')
            ->get();

        $response->getBody()->write(json_encode($rta));
        return $response;
    }
}