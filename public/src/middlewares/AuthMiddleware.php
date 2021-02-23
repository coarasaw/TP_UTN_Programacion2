<?php

namespace App\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class AuthMiddleware {

    public function __invoke (Request $request, RequestHandler $handler) {

        $jwt = true; // VALIDAR EL TOKEN

        if (!$jwt) {
            $response = new Response();

            $rta = array("rta" => "Prohibido pasar No Autenticado");

            $response->getBody()->write(json_encode($rta));

            return $response->withStatus(403);
        } else {
            $response = $handler->handle($request);
            $existingContent = (string) $response->getBody();

            $resp = new Response();
            $resp->getBody()->write($existingContent);

            return $resp;
        }

    }
}