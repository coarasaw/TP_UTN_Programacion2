<?php
namespace App\Controllers;
use App\Models\Mesa;
use App\Models\PedidoDetalle;
use DateTime;
use Illuminate\Support\Facades\Date;

class PedidosNoEntregadosController {

    public function get($request, $response, $args) {

        $fechainicio = $args['fechainicio'];
        $fechafin = $args['fechafin'];

        $cantidadPedido = PedidoDetalle::where('estado_pedido', '=', 'listo para servir')
        ->where('created_at', '>=', $fechainicio)
        ->where('created_at', '<=', $fechafin)
        ->count(); 
        
        if ($cantidadPedido > 0) {
                
                $detallePedido = PedidoDetalle::from('pedido_detalles as p') 
                ->where('estado_pedido', '=', 'listo para servir')
                ->where('p.created_at', '>=', $fechainicio)
                ->where('p.created_at', '<=', $fechafin)
                ->Join('menus','p.id_menu', '=' , 'menus.id_menu')
                ->select('p.tiempo_estimado','p.tiempo_inicio','p.estado_pedido','p.cantidad', 'menus.descripcion','p.nro_pedido','p.tiempo_fin')
                ->orderby('p.estado_pedido') 
                ->get();     
                    
                $pedidoCliente=(json_decode($detallePedido));       
                $arrayDeDatos = array();
                $j=0;
               
                for ($i = 0; $i < $cantidadPedido; $i++) {
                    if ($pedidoCliente[$i]->{'tiempo_estimado'} != 0) {
                    
                        $tiempoEstimado = "+".$pedidoCliente[$i]->{'tiempo_estimado'}." minute";
                        $tiempo_fin = new DateTime($pedidoCliente[$i]->{'tiempo_fin'});
                        $tiempo_inicio = new DateTime($pedidoCliente[$i]->{'tiempo_inicio'});
                        $tiempo_estipulado = $tiempo_inicio->modify($tiempoEstimado);
                            
                        if ($tiempo_fin > $tiempo_estipulado) {
                            $arrayDeDatos[$j] = "Pedido: " . $pedidoCliente[$i]->{'nro_pedido'}." Menu: " . $pedidoCliente[$i]->{'descripcion'} . " Estado: " .$pedidoCliente[$i]->{'estado_pedido'} . " Este no se entrego en el tiempo estipulado: " . $tiempo_estipulado->format('Y-m-d H:i:s') . " Tiempo real entregado: " .$tiempo_fin->format('Y-m-d H:i:s');
                            $j++;
                        } 
                        
                    }  
                }
                $rta = $arrayDeDatos; 
                $response->getBody()->write(json_encode($rta));
                return $response;
        } else {
            $rta = "No existe pedidos -> " . $cantidadPedido . " Ingrese un Pedido valido";
            $response->getBody()->write(json_encode($rta));
            return $response;
        }    
    }
}