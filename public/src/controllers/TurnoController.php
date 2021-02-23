<?php
namespace App\Controllers;
use App\Models\Turno;
use \Firebase\JWT\JWT;

class TurnoController {

    public function add($request, $response, $args)
    {
        //Token
        $headersEnvio = getallheaders();
        $key = "pro3-parcial";  
        $miToken = $headersEnvio["token"] ?? 'No mando Token';
        $decoded = JWT::decode($miToken, $key, array('HS256'));
        $decoded_array = (array) $decoded;
        //tomo email 
        $verificoEmail = $decoded_array['email'];
        
        //Obtemos datos del body
        $dato = $request->getParsedBody();
        $tipo = $dato['tipo']?? '';
        $fecha = $dato['fecha']?? '';
        $usuario_FK =  $verificoEmail; //$dato['usuario_FK'];
        
        if(isset($tipo) && isset($fecha) && isset($usuario_FK)){
            if ($tipo == "perro" or $tipo == "gato" or $tipo == "huron" ) {
                
                // Graba en la Base de Datos
                $user = new Turno;                      
                $user->tipo = $tipo;
                $user->fecha = $fecha;
                $user->usuario_FK = $usuario_FK;
                
                $rta = $user->save();
                $rta = "Se grabo el Turno";
                $response->getBody()->write(json_encode($rta));
                return $response;
            }else{
                $rta = "Error en Tipo de Datos para Sacar Turno";
                $response->getBody()->write(json_encode($rta));
                return $response;
            }
            
        }else{
            $rta = "Error en la carga de Datos para Sacar Turno";
            $response->getBody()->write(json_encode($rta));
            return $response;
        }
    }

    public function getAll ($request, $response, $args) {
        
        $rta = Turno::from('turnos as t')
               ->Join('mascotas','t.tipo', '=' , 'mascotas.tipo')   //leftJoin - rightJoin - crossJoin - Join
               ->Join('users','t.usuario_FK', '=' , 'users.email')
               ->select('users.nombre','t.tipo', 't.fecha','mascotas.precio')
               ->get();

        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function update($request, $response, $args)
    {   
        $id = $args['id'];
        $user = Turno::find($id);
        $user->atendido = "si";

        $rta = $user->save();
        if ($rta) {
            $rta = "Turno Atendido";
            $response->getBody()->write(json_encode($rta));
            return $response;
        }else{
            $rta = "ERROR en Turno Atendido";
            $response->getBody()->write(json_encode($rta));
            return $response;
        }
    }
}