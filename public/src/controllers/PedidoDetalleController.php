<?php
namespace App\Controllers;
use App\Models\PedidoCabecera;
use App\Models\PedidoDetalle;
use App\Models\Menu;
use App\Models\Usuarios;
use \Firebase\JWT\JWT;

class PedidoDetalleController {

    public function add($request, $response, $args)
    {
        //Obtemos datos del body
        $dato = $request->getParsedBody();
        $nro_pedido = $dato['nro_pedido']?? '';
        $id_menu = $dato['id_menu']?? '';
        $cantidad = $dato['cantidad']?? '';
        
        if(isset($nro_pedido) && isset($id_menu) && isset($cantidad)){
            //Buscar Pedido Cabecera
            $count = PedidoCabecera::where('nro_pedido', '=', $nro_pedido)->count(); 
            
            if ($count > 0) {
                   //Buscar Menu
                    $count = Menu::where('id_menu', '=', $id_menu)->count(); 
                    
                    if ($count > 0) {
                        $users = Menu::where('id_menu', '=', $id_menu)->get();
                        
                        foreach ($users as $user) 
                        {
                            $descripcion = $user->descripcion;
                            $importe = $user->importe;
                        }
                            $userDetalle = new PedidoDetalle;                       
                            $userDetalle->nro_pedido = $nro_pedido;
                            $userDetalle->id_menu = $id_menu;
                            $userDetalle->cantidad = $cantidad;
                            $userDetalle->importe_parcial = $importe*$cantidad;

                            $rta = $userDetalle->save();
                            if ($rta) {
                                $rta = "Se grabo el Detale Pedido";
                                $response->getBody()->write(json_encode($rta));
                                return $response;
                            }else{
                                $rta = "ERROR en grabar la Detale Pedido";
                                $response->getBody()->write(json_encode($rta));
                                return $response;
                            }
                    } else {    
                            $rta = "ERROR: Menu no existe";
                            $response->getBody()->write(json_encode($rta));
                            return $response;
                    }
            } else {    
                    $rta = "ERROR: Pedido no existe en Cabecera, dar de Alta Cabecera";
                    $response->getBody()->write(json_encode($rta));
                    return $response;
            }    
        }else{
            $response->getBody()->write(json_encode("Error en la carga de Datos para Detalle Pedido"));
            return $response;
        }
    }

    public function getAll ($request, $response, $args) {
        /* $rta = PedidoDetalle::where('estado_pedido', '=', 'Pendiente') 
        ->get(); */  
        
        $headersEnvio = getallheaders();
        
        // llave
        try {
            $key = "pro3-parcial";  
            $miToken = $headersEnvio["token"] ?? 'No mando Token'; 
           
            if (isset($miToken)){
                $decoded = JWT::decode($miToken, $key, array('HS256'));
                $decoded_array = (array) $decoded;
                $id = $decoded_array['email'];
               
                $count = Usuarios::where('email', '=', $id)->count();
               
                if ($count > 0) {

                        $users = Usuarios::where('email', '=', $id)->get();
                            
                        foreach ($users as $user) 
                        {
                            $id_sector = $user->id_sector;
                        }

                        $rta = PedidoDetalle::from('pedido_detalles as p') 
                                ->where('estado_pedido', '=', 'Pendiente')
                                ->Join('menus','p.id_menu', '=' , 'menus.id_menu')
                                ->where('id_sector', '=', $id_sector)
                                ->select('p.estado_pedido','p.cantidad', 'menus.id_sector','menus.descripcion')
                                ->get();
                        
                        
                        $response->getBody()->write(json_encode($rta));
                        return $response;
                    
                } else {
                    $response->getBody()->write(json_encode("NO Encontro Usuario"));
                    return $response;      
                }    
            }else{
                $rta = array("No Mando [TOKEN]");
                $response->getBody()->write(json_encode($rta));
                return $response;
            }  
        }
        catch (\Throwable $th) {
            $rta = array("ERROR: No tiene permisos [TOKEN]");
            $response->getBody()->write(json_encode($rta));
            return $response;
        }
    }

    public function update($request, $response, $args)
    {   
        $id = $args['id'];
        $id_menu = $args['id_menu'];
        $estado_pedido = $args['estado_pedido'];
        $t_estimado = $args['t_estimado'];
        $email = $args['email'];
      
        $count = PedidoDetalle::where('nro_pedido', '=', $id) 
        ->where('id_menu', '=', $id_menu)
        ->count();

        if($count > 0){
            if ($estado_pedido == "cancelado") {
                $rta = PedidoDetalle::where('nro_pedido', '=', $id)
                ->where('id_menu', '=', $id_menu)
                ->update(['estado_pedido' => $estado_pedido,'email' => $email,'tiempo_inicio' => date("Y-m-d H:i:s")]);
                if ($rta) {
                    $rta = "Estado Comanda es ".$estado_pedido." por ".$email;
                    $response->getBody()->write(json_encode($rta));
                    return $response;
                }else{
                    $rta = "ERROR en Pedido Detalle Actualización";
                    $response->getBody()->write(json_encode($rta));
                    return $response;
                }
            }else{
                $rtaVerEstasoPedido = PedidoDetalle::where('nro_pedido', '=', $id) 
                ->where('id_menu', '=', $id_menu)
                ->get();
                foreach ($rtaVerEstasoPedido as $user) 
                {
                    $tomo_estado_pedido = $user->estado_pedido;
                }
                if ($tomo_estado_pedido == "Pendiente") {
                    $rta = PedidoDetalle::where('nro_pedido', '=', $id)
                    ->where('id_menu', '=', $id_menu)
                    ->update(['estado_pedido' => $estado_pedido,'tiempo_estimado' => $t_estimado,'email' => $email,'tiempo_inicio' => date("Y-m-d H:i:s")]);
                    
                    if ($rta) {
                        $rta = "Estado Comanda es ".$estado_pedido." Preparada por ".$email;
                        $response->getBody()->write(json_encode($rta));
                        return $response;
                    }else{
                        $rta = "ERROR en Pedido Detalle Actualización";
                        $response->getBody()->write(json_encode($rta));
                        return $response;
                    }
                }else{
                        $rtaVerEstasoPedido = PedidoDetalle::where('nro_pedido', '=', $id) 
                        ->where('id_menu', '=', $id_menu)
                        ->get();
                        foreach ($rtaVerEstasoPedido as $user) 
                        {
                            $tomo_estado_pedido = $user->estado_pedido;
                        }
                        if($tomo_estado_pedido == "en preparación" && $estado_pedido == "listo para servir") {  
                            $rta = PedidoDetalle::where('nro_pedido', '=', $id)
                            ->where('id_menu', '=', $id_menu)
                            ->update(['estado_pedido' => $estado_pedido,'tiempo_fin' => date("Y-m-d H:i:s")]);
                            
                            if ($rta) {
                                $rta = "Estado Comanda es ". $estado_pedido." Preparada por ".$email;
                                $response->getBody()->write(json_encode($rta));
                                return $response;
                            }else{
                                $rta = "ERROR en Pedido Detalle Actualización";
                                $response->getBody()->write(json_encode($rta));
                                return $response;
                            }
                        }else{
                            $rta = "ERROR en Pedido Detalle Estado ". $tomo_estado_pedido . " Incorrecto ";
                            $response->getBody()->write(json_encode($rta));
                            return $response;
                        }
                    }
                }
            
        }else{
            $rta = "ERROR en Pedido Detalle No Existe";
            $response->getBody()->write(json_encode($rta));
            return $response;
        }
    }

    public function delete($request, $response, $args)
    {
        $id = $args['id'];
        $baja = $args['baja'];
        var_dump($id);
        var_dump($baja);
        die();
        $user = PedidoCabecera::find($id);
        $user->baja = $baja;
        $rta = $user->save();
        //$rta = $user->delete();

        if ($rta) {
            $rta = "Empleado de Baja";
            $response->getBody()->write(json_encode($rta));
            return $response;
        }else{
            $rta = "ERROR en Empleado";
            $response->getBody()->write(json_encode($rta));
            return $response;
        }
    }
}