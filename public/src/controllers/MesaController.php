<?php
namespace App\Controllers;
use App\Models\Mesa;
use \Firebase\JWT\JWT;

class MesaController {

    public function add($request, $response, $args)
    {
        //Obtemos datos del body
        $dato = $request->getParsedBody();
        $id = $dato['id']?? '';
        //$estado_Mesa = $dato['estado_Mesa']?? '';

        if(isset($id) ){     //&& isset($estado_Mesa)
            //Buscar Mesa
            $count = Mesa::where('id', '=', $id)->count(); //Verifica mesa unica
            if ($count > 0) {
                $response->getBody()->write(json_encode("Encontro Mesa -> YA EXISTE"));
                return $response;
            } else { 
                    if(strlen($id) < 5){
                        $response->getBody()->write(json_encode("ID debe tener 5 caracteres"));
                        return $response;
                    }
                    //if ($estado_Mesa == "con cliente esperando pedido" or $estado_Mesa == "con clientes comiendo"  or $estado_Mesa == "con clientes pagando" or $estado_Mesa == "cerrada") {
                        // Graba en la Base de Datos
                        $user = new Mesa;                       
                        $user->id = $id;
                        //$user->estado_Mesa = $estado_Mesa;
                        
                        $rta = $user->save();
                        if ($rta) {
                            $rta = "Alta de Mesa";
                            $response->getBody()->write(json_encode($rta));
                            return $response;
                        }else{
                            $rta = "ERROR en grabar la Mesa";
                            $response->getBody()->write(json_encode($rta));
                            return $response;
                        }
                    /* }else{
                        $response->getBody()->write(json_encode("Error de Estado para la Mesa"));
                        return $response;
                    } */
            }    
        }else{
            $response->getBody()->write(json_encode("Error en la carga de Datos para Mesa"));
            return $response;
        }
    }

    public function update($request, $response, $args)
    {   
        $id = $args['id'];
        $estado_Mesa = $args['estado_Mesa'];
       
        /* var_dump($estado_Mesa);
        die(); */

        if ($estado_Mesa == "con cliente esperando pedido" or $estado_Mesa == "con clientes comiendo"  or $estado_Mesa == "con clientes pagando") {
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
            $rta = "ERROR: Estado Mesa ".$estado_Mesa. "Incorrecto debe ser [con cliente esperando pedido,con clientes comiendo,con clientes pagando]";
            $response->getBody()->write(json_encode($rta));
            return $response;
        }
        
    }
}