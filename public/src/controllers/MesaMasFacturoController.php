<?php
namespace App\Controllers;
use App\Models\PedidoCabecera;

class MesaMasFacturoController {

    public function getAll ($request, $response, $args) {
        
        $fechainicio = $args['fechainicio'];
        $fechafin = $args['fechafin'];
        
        $rta = PedidoCabecera::from('pedido_cabeceras as p')
            ->where('p.created_at', '>=', $fechainicio)
            ->where('p.created_at', '<=', $fechafin)
            ->select('p.id_mesa as Mesa ',PedidoCabecera::raw('sum(p.importe_total) as Cantidad_Facturo'))
            ->groupBy('p.id_mesa')
            ->orderBydesc('Cantidad_Facturo')
            ->take(1)
            ->get();

        $response->getBody()->write(json_encode($rta));
        return $response;
    }
}