<?php
namespace App\Controllers;
use App\Models\Vitacorasempleado;
use Clases\Usuario; 
use \Firebase\JWT\JWT;

class EmpleadoSistemaController {

    public function getAll ($request, $response, $args) {
        
        $headersEnvio = getallheaders();
        $fechainicio = $args['fechainicio'];
        $fechafin = $args['fechafin'];
        
        $rta = Vitacorasempleado::from('vitacorasempleados as v') 
                                 ->where('v.fecha_logueo', '>=', $fechainicio)
                                 ->where('v.fecha_logueo', '<=', $fechafin)
                                 ->Join('usuarios','v.id_empleado', '=' , 'usuarios.id')
                                 ->select('usuarios.nombre','usuarios.email', 'v.fecha_logueo','v.hora_logueo')
                                 ->get();

        $response->getBody()->write(json_encode($rta));
        return $response;
    }
}