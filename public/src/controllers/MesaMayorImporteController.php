<?php
namespace App\Controllers;
use App\Models\PedidoCabecera;

class MesaMayorImporteController {

    public function getAll ($request, $response, $args) {
        
        $fechainicio = $args['fechainicio'];
        $fechafin = $args['fechafin'];
        
        $rta = PedidoCabecera::from('pedido_cabeceras as p')
            ->where('p.created_at', '>=', $fechainicio)
            ->where('p.created_at', '<=', $fechafin)
            ->selectRaw('MAX(p.importe_total) AS Importe_Mayor_Factura')
            ->get();
           
        $response->getBody()->write(json_encode($rta));
        return $response;
    }
}