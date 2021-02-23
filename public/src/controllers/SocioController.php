<?php
namespace App\Controllers;
use App\Models\Mesa;
use App\Models\PedidoDetalle;
use App\Models\PedidoCabecera;
use DateTime;

class SocioController {

    public function update($request, $response, $args)
    {   
        $id = $args['id'];
        $estado_Mesa = $args['estado_Mesa'];
        $nro_pedido = $args['nro_pedido'];

        if ($estado_Mesa == "cerrada") {
            //Sumamos los importes parciales pedido detalle.
            //Para obtener el importe total del pedido.

            $cantidadPedido = PedidoDetalle::where('nro_pedido', '=', $nro_pedido)
                            ->where('estado_pedido', '=', 'listo para servir')
                            ->count(); 
            
            if ($cantidadPedido > 0) {
                    
                    $detallePedido = PedidoDetalle::from('pedido_detalles as p') 
                    ->where('nro_pedido', '=', $nro_pedido)
                    ->where('estado_pedido', '=', 'listo para servir')
                    ->select(PedidoDetalle::raw('sum(p.importe_parcial) as ImporteTotal'),'p.nro_pedido')
                    ->get();     
                        
                    $pedidoCliente=(json_decode($detallePedido)); 
                    $importeTotalGuardar = $pedidoCliente[0]->{'ImporteTotal'};

                    //Guardar importe Total en cabecera. 
                    $rtaPedidoCabecera = PedidoCabecera::where('nro_pedido', '=', $nro_pedido)
                    ->update(['importe_total' => $importeTotalGuardar]);
                    
            } else {
                $rta = "No existe pedidos para actulaizar en Cabecera -> " . $cantidadPedido . " Ingrese un Pedido valido";
                $response->getBody()->write(json_encode($rta));
                return $response;
            }

            //
            $user = Mesa::find($id);
            $user->estado_Mesa = $estado_Mesa;
            
            $rta = $user->save();   
            if ($rta) {
                $rta = "Estado Mesa Actaualizado a -> ".$estado_Mesa;
                $response->getBody()->write(json_encode($rta));
                return $response;
            }else{
                $rta = "ERROR en Actualizar Estado Mesa";
                $response->getBody()->write(json_encode($rta));
                return $response;
            }
        }else{
            $rta = "ERROR: Estado Mesa ".$estado_Mesa. "Incorrecto debe ser [cerrada]";
            $response->getBody()->write(json_encode($rta));
            return $response;
        }
        
    }

    public function getAll ($request, $response, $args) {
        
        $rta = PedidoDetalle::from('pedido_detalles as p') 
                                ->Join('menus','p.id_menu', '=' , 'menus.id_menu')
                                ->select('p.estado_pedido', 'p.nro_pedido','menus.descripcion')
                                ->get();
        
        $response->getBody()->write(json_encode($rta));
        return $response;
        
    }
}