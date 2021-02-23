<?php
namespace App\Controllers;
use App\Models\Mesa;
use App\Models\PedidoDetalle;
use DateTime;
use Illuminate\Support\Facades\Date;

class ClienteController {

    public function get ($request, $response, $args) {

        $nro_pedido = $args['nro_pedido'];

        $cantidadPedido = PedidoDetalle::where('nro_pedido', '=', $nro_pedido)
                          ->where('estado_pedido', '<>', 'cancelado')
                          //->orwhere('estado_pedido', '=', 'Pendiente')
                          ->count(); 
        
        if ($cantidadPedido > 0) {
                
                $detallePedido = PedidoDetalle::from('pedido_detalles as p') 
                ->where('nro_pedido', '=', $nro_pedido)
                ->where('estado_pedido', '<>', 'cancelado')
                ->Join('menus','p.id_menu', '=' , 'menus.id_menu')
                ->select('p.tiempo_estimado','p.tiempo_inicio','p.estado_pedido','p.cantidad', 'menus.descripcion','p.nro_pedido')
                ->orderby('p.estado_pedido') 
                ->get();     
                    
                $pedidoCliente=(json_decode($detallePedido));       
                $arrayDeDatos = array();
                $j=0;
               
                for ($i = 0; $i < $cantidadPedido; $i++) {
                    if ($pedidoCliente[$i]->{'tiempo_estimado'} == 0) {
                        $arrayDeDatos[$j] = "Pedido: " . $pedidoCliente[$i]->{'nro_pedido'}." Menu: " . $pedidoCliente[$i]->{'descripcion'} . " Estado: " .$pedidoCliente[$i]->{'estado_pedido'} . " Tiempo restante: Indeterminado";
                        $j++;
                    } else {
                        $tiempoActual = date('Y-m-d H:i:s');
                        $tiempoInicio = $pedidoCliente[$i]->{'tiempo_inicio'};
                        $tiempoEstimado = "+".$pedidoCliente[$i]->{'tiempo_estimado'}." minute";
                        $horaEstimada = new DateTime($tiempoInicio);
                        $horaEstimada->modify($tiempoEstimado); 
                        $datetime1 = date_create($tiempoActual);
                        $datetime2 = date_create($horaEstimada->format('Y-m-d H:i:s'));
                        $interval = date_diff($datetime1, $datetime2);
                        $demora = explode(':',$interval->format('%H:%i:%s'));
                        $arrayDeDatos[$j] = "Pedido: " . $pedidoCliente[$i]->{'nro_pedido'}." Menu: " . $pedidoCliente[$i]->{'descripcion'} . " Estado: " .$pedidoCliente[$i]->{'estado_pedido'} . " Tiempo restante para su Pedido: " . $demora[0].":". $demora[1].":".$demora[2];
                        $j++;
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