<?php
namespace App\Controllers;
use App\Models\PedidoCabecera;

class MesaMenorImporteController {

    public function getAll ($request, $response, $args) {
        
        $fechainicio = $args['fechainicio'];
        $fechafin = $args['fechafin'];
        
        $rta = PedidoCabecera::from('pedido_cabeceras as p')
            ->where('p.created_at', '>=', $fechainicio)
            ->where('p.created_at', '<=', $fechafin)
            ->selectRaw('MIN(p.importe_total) AS Importe_Menor_Factura')
            ->get();
           
        $response->getBody()->write(json_encode($rta));
        return $response;
    }
}