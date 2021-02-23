<?php
namespace App\Controllers;
use App\Models\Usuarios;
use \Firebase\JWT\JWT;

class EmpleadoController {

    public function update($request, $response, $args)
    {   
        $id = $args['id'];
        $suspension = $args['suspension'];
        $baja = $args['baja'];
        
        $user = Usuarios::find($id);
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
        $user = Usuarios::find($id);
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