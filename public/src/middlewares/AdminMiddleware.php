<?php

namespace App\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use \Firebase\JWT\JWT;

class AdminMiddleware {

    public function __invoke (Request $request, RequestHandler $handler) {

        $headersEnvio = getallheaders();
        // llave
        try {
            $key = "pro3-parcial";  
            $miToken = $headersEnvio["token"] ?? 'No mando Token'; // Si se genero el Token aca lo obtengo de la cabecera

            if (isset($miToken)){
                $decoded = JWT::decode($miToken, $key, array('HS256'));
                $decoded_array = (array) $decoded;
                $verificoTipo = $decoded_array['tipo'];
                /* var_dump($verificoTipo);
                die(); */
                if ($verificoTipo == "admin") {
                    $response = $handler->handle($request);
                    $existingContent = (string) $response->getBody();
    
                    $resp = new Response();
                    $resp->getBody()->write($existingContent);
    
                    return $resp;
                }else{
                    $response = new Response();
                    $rta = array("No es Admin [TOKEN]");
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
            $rta = array("ERROR Admin: No tiene permisos [TOKEN]");
            $response->getBody()->write(json_encode($rta));
            return $response;
        }
    }
}