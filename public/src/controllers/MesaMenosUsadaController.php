<?php
namespace App\Controllers;
use App\Models\PedidoCabecera;

class MesaMenosUsadaController {

    public function getAll ($request, $response, $args) {
        
        $fechainicio = $args['fechainicio'];
        $fechafin = $args['fechafin'];
        
        $rta = PedidoCabecera::from('pedido_cabeceras as p')
            ->where('p.created_at', '>=', $fechainicio)
            ->where('p.created_at', '<=', $fechafin)
            ->select('p.id_mesa as Mesa ',PedidoCabecera::raw('count(p.nro_pedido) as Cantidad_Pedido'))
            ->groupBy('p.id_mesa')
            ->orderBy('Cantidad_Pedido')
            ->take(1)
            ->get();

        $response->getBody()->write(json_encode($rta));
        return $response;
    }
}