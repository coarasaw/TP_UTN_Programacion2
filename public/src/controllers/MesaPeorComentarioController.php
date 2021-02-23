<?php
namespace App\Controllers;
use App\Models\Encuesta;

class MesaPeorComentarioController {

    public function getAll ($request, $response, $args) {
        
        $fechainicio = $args['fechainicio'];
        $fechafin = $args['fechafin'];
        
        $rta = Encuesta::from('encuestas as e')
            ->where('e.created_at', '>=', $fechainicio)
            ->where('e.created_at', '<=', $fechafin)
            ->select('comentario',Encuesta::raw('(e.puntuacion_mesa + e.puntuacion_mozo + e.puntuacion_cocinero + e.puntuacion_restaurante) as Puntuacion'))
            ->orderBy('Puntuacion')
            ->take(1)
            ->get();
           
        $response->getBody()->write(json_encode($rta));
        return $response;
    }
}