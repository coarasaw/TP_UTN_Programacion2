<?php
namespace App\Controllers;
use App\Models\Mesa;
use App\Models\Encuesta;
use \Firebase\JWT\JWT;

class EncuestaController {

    public function add($request, $response, $args)
    {
        //Obtemos datos del body
        $dato = $request->getParsedBody();
        $id_mesa = $dato['id_mesa']?? '';
        $puntuacion_mesa = $dato['puntuacion_mesa']?? '';
        $id_mozo = $dato['id_mozo']?? '';
        $puntuacion_mozo = $dato['puntuacion_mozo']?? '';
        $id_cocinero = $dato['id_cocinero']?? '';
        $puntuacion_cocinero = $dato['puntuacion_cocinero']?? '';
        $puntuacion_restaurante = $dato['puntuacion_restaurante']?? '';
        $comentario = $dato['comentario']?? '';

        if(isset($id_mesa) && isset($puntuacion_mesa) && isset($id_mozo) && isset($puntuacion_mozo) && isset($id_cocinero) && isset($puntuacion_cocinero) && isset($puntuacion_restaurante) && isset($comentario)){     
            //Buscar Mesa
            $count = Mesa::where('id', '=', $id_mesa)->count(); 
            if ($count > 0) {
                $user = new Encuesta;                       
                $user->id_mesa = $id_mesa;
                $user->puntuacion_mesa = $puntuacion_mesa;
                $user->id_mozo = $id_mozo;
                $user->puntuacion_mozo = $puntuacion_mozo;
                $user->id_cocinero = $id_cocinero;
                $user->puntuacion_cocinero = $puntuacion_cocinero;
                $user->puntuacion_restaurante = $puntuacion_restaurante;
                $user->comentario = $comentario;

                $rta = $user->save();
                        if ($rta) {
                            $rta = "Alta de Encuesta";
                            $response->getBody()->write(json_encode($rta));
                            return $response;
                        }else{
                            $rta = "ERROR en grabar la Encuesta";
                            $response->getBody()->write(json_encode($rta));
                            return $response;
                        }
            } else { 
                    
                $response->getBody()->write(json_encode("Mesa -> NO EXISTE"));
                return $response;
            }    
        }else{
            $response->getBody()->write(json_encode("Error en la carga de Datos para Encuesta"));
            return $response;
        }
    }
    
}