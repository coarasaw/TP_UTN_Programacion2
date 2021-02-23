<?php
namespace App\Controllers;
use App\Models\PedidoDetalle;
use App\Models\Menu;

use \Firebase\JWT\JWT;

class OperacionEmpleadoController {

    public function getAll ($request, $response, $args) {
        
        $headersEnvio = getallheaders();
        $fechainicio = $args['fechainicio'];
        $fechafin = $args['fechafin'];

        $rta = PedidoDetalle::from('pedido_detalles as p')
            ->where('p.created_at', '>=', $fechainicio)
            ->where('p.created_at', '<=', $fechafin)
            ->select('email as Empleado',PedidoDetalle::raw('count(p.id_menu) as Cantidad'))
            ->groupBy('email')
            ->get();

        $response->getBody()->write(json_encode($rta));
        return $response;
    }
}