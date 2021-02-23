<?php
namespace App\Controllers;
use App\Models\PedidoCabecera;

class MesaMasUsadaController {

    public function getAll ($request, $response, $args) {
        
        $fechainicio = $args['fechainicio'];
        $fechafin = $args['fechafin'];
        
        
        $rta = PedidoCabecera::from('pedido_cabeceras as p')
            
            ->select('p.id_mesa as Mesa ',PedidoCabecera::raw('count(p.nro_pedido) as Cantidad_Pedido'))
            ->groupBy('p.id_mesa')
            ->orderBydesc('Cantidad_Pedido')
            ->take(1)
            ->get();

        $response->getBody()->write(json_encode($rta));
        return $response;
    }
}