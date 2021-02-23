<?php
namespace App\Controllers;
use App\Models\Usuarios;
use App\Models\Vitacorasempleado;
//use Clases\Usuario; 
use \Firebase\JWT\JWT;

class LoginController {

    public function crea_token($request, $response, $args)
    {
        //Obtemos datos del body
        $dato = $request->getParsedBody();
        $usuario = $dato['usuario']?? '';
        $clave = $dato['clave']?? '';
        //Buscar email
        if($usuario <> '' && $clave <> ''){
            $count = Usuarios::where('email', '=', $usuario)->count();
           
            if ($count > 0) {
                $users = Usuarios::where('email', '=', $usuario)->get();
                
                foreach ($users as $user) 
                {
                    $id = $user->id;
                    $claveGuardada = $user->clave;
                    $emailEncontrado = $user->email;
                    $perfil = $user->perfil;
                }

                if (strcmp($usuario, $emailEncontrado) !== 0) {
                    $response->getBody()->write(json_encode("Error mayuscula, minusculas en el email"));
                    return $response;
                }else{
                    //Clave encriptada
                    $claveATratar = $clave;
                    $alt = "f#@V)Hu^%Hgfds";
                    $clave = sha1($alt.$claveATratar); 
                    
                    if($claveGuardada == $clave){
                        //Genero Token
                        // llave
                        $key = "pro3-parcial";  
                        //Generamos el Payload-CargaDatos
                        $payload = array(
                            "iss" => "http://example.org",
                            "aud" => "http://example.com",
                            "iat" => 1356999524,               //vencimiento del token
                            "nbf" => 1357000000,
                            "email" => $usuario,
                            "clave" => $clave,
                            "perfil" => $perfil
                        );
                        $jwt = JWT::encode($payload, $key);
                        //Vitacora
                        $userVitacora = new Vitacorasempleado;  
                        $userVitacora->id_empleado = $id;
                        
                        $userVitacora->fecha_logueo = date('Y-m-d');
                        $userVitacora->hora_logueo =  date("H:i:s");
                       
                        $rtaVitacora = $userVitacora->save();
                        //
                        $response->getBody()->write(json_encode($jwt));
                        return $response;
                    }else{
                        $response->getBody()->write(json_encode("Clave Erronea"));
                        return $response;
                    }
                }
            }
        }else{    
                $response->getBody()->write(json_encode("Datos Enviados Erroneos"));
                return $response;
        }
    }
}