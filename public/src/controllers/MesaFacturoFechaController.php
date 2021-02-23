<?php
namespace App\Controllers;
use App\Models\PedidoCabecera;

class MesaFacturoFechaController {

    public function getAll ($request, $response, $args) {
        
        $fechainicio = $args['fechainicio'];
        $fechafin = $args['fechafin'];
        $id_mesa = $args['id_mesa'];
        
        $rta = PedidoCabecera::from('pedido_cabeceras as p')
            ->where('p.created_at', '>=', $fechainicio)
            ->where('p.created_at', '<=', $fechafin)
            ->where('p.id_mesa', '=', $id_mesa)
            ->select('p.id_mesa as Mesa ',PedidoCabecera::raw('sum(p.importe_total) as Facturo'))
            ->groupBy('p.id_mesa')
            ->get();
           
        $response->getBody()->write(json_encode($rta));
        return $response;
    }
}