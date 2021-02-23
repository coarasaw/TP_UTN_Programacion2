<?php
namespace App\Controllers;
//use Psr\Http\Message\UploadedFileInterfacey; 
use App\Models\Usuarios;
use Clases\file;
use Img\Imagenes;


class UsuariosController {

    public function add($request, $response, $args)
    {
        //Obtemos datos del body
        $dato = $request->getParsedBody();
        $email = $dato['email']?? '';
        $clave = $dato['clave']?? '';
        $nombre = $dato['nombre']?? '';
        $perfil = $dato['perfil']?? '';
        $id_sector = $dato['id_sector']?? '';  

        //Buscar email
        $count = Usuarios::where('email', '=', $email)->count(); //Verifica email unico
    
        if ($count > 0) {
            $response->getBody()->write(json_encode("Encontro Usuario -> YA EXISTE"));
            return $response;
        } else { 
                    if(strlen($clave) < 4){
                        $response->getBody()->write(json_encode("Clave debe tener al menos 4 caracteres"));
                        return $response;
                    }
                    // perfil --> Cocinero/Bartender/Cervecero/Mozo/Socio
                    if ($perfil <> "Cocinero" && $perfil <> "Bartender" && $perfil <> "Cervecero" && $perfil <> "Mozo" && $perfil <> "Socio") {
                        $response->getBody()->write(json_encode("Perfil debe ser: [Cocinero;Bartender;Cervecero;Mozo;Socio]"));
                        return $response;
                    }
                    // sector (Cocina/Candy bar/Barra de tragos y vinos/Barra de choperas de cerveza artesanal)
                    if ($id_sector <> "Cocina" && $id_sector <> "Candy bar" && $id_sector <> "Barra de tragos y vinos" && $id_sector <> "Barra de choperas de cerveza artesanal" && $id_sector <> "sin sector") {
                        $response->getBody()->write(json_encode("Sector debe ser: [Cocina/Candy bar/Barra de tragos y vinos/Barra de choperas de cerveza artesanal]"));
                        return $response;
                    }
                    switch ($id_sector) {
                        case 'Cocina':
                            if ($perfil <> "Cocinero") {
                                $response->getBody()->write(json_encode("En el sector Cocina el Perfil debe ser: [Cocinero]"));
                                return $response;
                            }
                            break;
                        case 'Candy bar':
                            if ($perfil <> "Cocinero") {
                                $response->getBody()->write(json_encode("En el sector Candy bar el Perfil debe ser: [Cocinero]"));
                                return $response;
                            }   
                            break;
                        case 'Barra de tragos y vinos':
                            if ($perfil <> "Bartender") {
                                $response->getBody()->write(json_encode("En el sector Barra de tragos y vinos el Perfil debe ser: [Bartender]"));
                                return $response;
                            }
                            break;
                        case 'Barra de choperas de cerveza artesanal':
                            if ($perfil <> "Cervecero") {
                                $response->getBody()->write(json_encode("En el sector Barra de choperas de cerveza artesanal el Perfil debe ser: [Cervecero]"));
                                return $response;
                            }
                            break;
                            case 'sin sector':
                                if ($perfil <> "Mozo" && $perfil <> "Socio") {
                                    $response->getBody()->write(json_encode("En el sector sin sector el Perfil debe ser: [Mozo o Socio]"));
                                    return $response;
                                }
                                break;        
                        default:
                            $response->getBody()->write(json_encode("Error inesperado en la combinacion Perfil / Sector"));
                            return $response;
                            break;
                    }
                }
            //Clave encriptada
            $claveATratar = $clave;
	        $alt = "f#@V)Hu^%Hgfds";
            $clave = sha1($alt.$claveATratar);    
    
            // Graba en la Base de Datos
            $user = new Usuarios;  // creo una clase de usuario
            $user->email = $email;
            $user->clave = $clave;
            $user->nombre = $nombre;
            $user->perfil = $perfil;
            $user->id_sector = $id_sector;

            $rta = $user->save();
            if ($rta==true) {
                $rta = "Grabo con Exito el Usuario";
            }else{
                $rta = "NO --> Grabo Usuario";
            }

            $response->getBody()->write(json_encode($rta));
            return $response;
    }

    public function getAll ($request, $response, $args) {
        $rta = Usuarios::get();  // Trae Todos los de la base
        //$rta = User::find(1);
        //$rta = User::where('id', '>',  0)   // Se usa para obtener un rango.
        // ->where('campo', 'operador', 'valor')        
        //->get();

        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function getOne($request, $response, $args)
    {
        $id = $args['id'];
        $rta = Usuarios::find($id);
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function update($request, $response, $args)
    {   
        $id = $args['id'];
        $suspension = $args['suspension'];
        $baja = $args['baja'];
    
        $rta = Usuarios::where('id', '=', $id)
        ->update(['suspension' => $suspension,'baja' => $baja]);
         
        if ($rta) {
            $rta = "Empleado Suspendido/Baja Modificado";
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
        $user = Usuarios::find($id);
        $rta = $user->delete();

        $response->getBody()->write(json_encode($rta));
        return $response;
    }
}