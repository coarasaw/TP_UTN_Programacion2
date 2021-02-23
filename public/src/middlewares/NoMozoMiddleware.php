<?php

namespace App\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use \Firebase\JWT\JWT;

class NoMozoMiddleware {

    public function __invoke (Request $request, RequestHandler $handler) {

        $headersEnvio = getallheaders();
        // llave
        try {
            $key = "pro3-parcial";  
            $miToken = $headersEnvio["token"] ?? 'No mando Token'; 
           
            if (isset($miToken)){
                $decoded = JWT::decode($miToken, $key, array('HS256'));
                $decoded_array = (array) $decoded;
                $verificoPerfil = $decoded_array['perfil'];
                 
                if ($verificoPerfil != "Mozo" or $verificoPerfil == "Socio") {

                    $response = $handler->handle($request);
                    $existingContent = (string) $response->getBody();
                   
                    $resp = new Response();
                    $resp->getBody()->write($existingContent);
    
                    return $resp;
                }else{
                    
                    $response = new Response();
                    $rta = array("No es Mozo [TOKEN]");
                    $response->getBody()->write(json_encode($rta));
                    return $response;
                } 
            }else{
                $response = new Response();
                $rta = array("No Mando [TOKEN]");
                $response->getBody()->write(json_encode($rta));
                return $response;
            }  
        }
        catch (\Throwable $th) {
            
            $response = new Response();
            $rta = array("ERROR Mozo: No tiene permisos [TOKEN]");
            $response->getBody()->write(json_encode($rta));
            return $response;
        }
    }
}