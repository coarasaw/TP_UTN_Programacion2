<?php
namespace App\Controllers;
use App\Models\PedidoCabecera;
use App\Models\Mesa;
use \Firebase\JWT\JWT;

class PedidoController {

    public function add($request, $response, $args)
    {
        //Obtemos datos del body
        $dato = $request->getParsedBody();
        $nro_pedido = $dato['nro_pedido']?? '';
        $id_mesa = $dato['id_mesa']?? '';
        $nombreCliente = $dato['nombreCliente']?? '';
        $nombreFoto = $_FILES['foto']['name']?? '';

        if(isset($nro_pedido) && isset($id_mesa) && isset($nombreCliente) && isset($nombreFoto)){
            //Buscar 
            $count = PedidoCabecera::where('nro_pedido', '=', $nro_pedido)->count(); 
            
            if ($count == 0) {
                //Foto
                if ($nombreFoto != '') {
                    $nombreImagen = $nro_pedido;          //explode('@', $email);
                    // extencion del archivo
                    $extension = explode('.', $nombreFoto);
                    $extension = $extension[1];
                    // armamos el nombre del archivo
                    $nonbreArchivo = $nombreImagen[0].time().'.'.$extension;
                    //de donde saco el archivo
                    $guardado=$_FILES['foto']['tmp_name'];
                    $subido = "Tomo Foto";
                } else {
                    $subido = "NO Tomo Foto";
                    $nonbreArchivo = '';
                }
                
                //Buscar mesas
                $count = Mesa::where('id', '=', $id_mesa)->count(); //Verifica mesa unica
                
                if ($count > 0) {
                    $users = Mesa::where('id', '=', $id_mesa)->get();
                    
                    foreach ($users as $user) 
                    {
                        $estado_Mesa = $user->estado_Mesa;
                    }
                    if ($subido != "NO Tomo Foto") {

                        if(!file_exists('imagenes')){
                            mkdir('imagenes',0777,true);
                            if(file_exists('imagenes')){
                                if(move_uploaded_file($guardado,'imagenes/'.$nonbreArchivo)){
                                    $subido = "Subio";
                                }else{
                                    $subido = "NO Subio";
                                }
                            }
                        }else{
                            if(move_uploaded_file($guardado,'imagenes/'.$nonbreArchivo)){
                                $subido = "Subio";
                            }else{
                                $subido = "NO Subio";
                            }
                        } 
                    }
                    
                    if ($estado_Mesa=="cerrada") {
                        $user = new PedidoCabecera;                       
                        $user->nro_pedido = $nro_pedido;
                        $user->id_mesa = $id_mesa;
                        $user->nombreCliente = $nombreCliente;
                        $user->foto_mesa_integrantes = $nonbreArchivo;
                        
                        $rta = $user->save();
                        if ($rta) {
                            $rta = "Se grabo el Cabecera Pedido - ".$subido;
                            $response->getBody()->write(json_encode($rta));
                            return $response;
                        }else{
                            $rta = "ERROR en grabar la Cabecera Pedido";
                            $response->getBody()->write(json_encode($rta));
                            return $response;
                        }
                        
                    }else{
                        $response->getBody()->write(json_encode("El estodo de la mesa debe ser Cerrado, para asignale un pedido. Estado es ".$estado_Mesa));
                        return $response;
                    }
                    
                } else {    
                        $rta = "ERROR: Mesa no existe";
                        $response->getBody()->write(json_encode($rta));
                        return $response;
                }    
            }else{
                $response->getBody()->write(json_encode("Pedido ya Exite"));
                return $response;
            }    
        }else{
            $response->getBody()->write(json_encode("Error en la carga de Datos para Cabecera Pedido"));
            return $response;
        }
    }

    public function update($request, $response, $args)
    {   
        $id = $args['id'];
        $suspension = $args['suspension'];
        $baja = $args['baja'];
        /* var_dump($suspension);
        die(); */
        $user = PedidoCabecera::find($id);
        $user->suspension = $suspension;
        $user->baja = $baja;
        $rta = $user->save();   
        if ($rta) {
            $rta = "Empleado Suspendido Modificado";
            $response->getBody()->write(json_encode($rta));
            return $response;
        }else{
            $rta = "ERROR en Empleado";
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