<?php
namespace App\Controllers;
use App\Models\Menu;
use \Firebase\JWT\JWT;

class MenuController {

    public function add($request, $response, $args)
    {
        //Obtemos datos del body
        $dato = $request->getParsedBody();
        $id_menu = $dato['id_menu']?? '';
        $id_sector = $dato['id_sector']?? '';
        $descripcion = $dato['descripcion']?? '';
        $importe = $dato['importe']?? '';
        
        if(isset($id_menu) && isset($id_sector) && isset($descripcion) && isset($importe)){     
            //Buscar Menu
            $count = Menu::where('id_menu', '=', $id_menu)->count(); //Verifica menu unico
            if ($count > 0) {
                $response->getBody()->write(json_encode("Encontro Menú -> YA EXISTE"));
                return $response;
            } else { 
                    if(strlen($id_menu) < 5){
                        $response->getBody()->write(json_encode("ID debe tener 5 caracteres"));
                        return $response;
                    }
                    if ($id_sector == "Cocina" or $id_sector == "Candy bar" or $id_sector == "Barra de tragos y vinos" or $id_sector == "Barra de choperas de cerveza artesanal") {
                        // Graba en la Base de Datos
                        $user = new Menu;                       
                        $user->id_menu = $id_menu;
                        $user->id_sector = $id_sector;
                        $user->descripcion = $descripcion;
                        $user->importe = $importe;
                        
                        $rta = $user->save();
                        if ($rta) {
                            $rta = "Se grabo Menú";
                            $response->getBody()->write(json_encode($rta));
                            return $response;
                        }else{
                            $rta = "ERROR en grabar el Menú";
                            $response->getBody()->write(json_encode($rta));
                            return $response;
                        }
                    }else{
                        $response->getBody()->write(json_encode("Error de Sector para la Menú"));
                        return $response;
                    }
            }    
        }else{
            $response->getBody()->write(json_encode("Error en la carga de Datos para Menú"));
            return $response;
        }
    }
}