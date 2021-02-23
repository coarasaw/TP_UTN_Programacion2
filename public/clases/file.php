<?php
namespace Clases;
use App\Models\User;

class DATOS {

    public static function guardarDatos($ruta, $objeto)
    {
        
    }


    static public function LeerDato($dato) {

        $rta = User::find($dato);
        return $rta;
        
    }

    //static public function tratarArchivo($archivoTrabajar,$id){ // $id este caso es si quiero usar un campo por ej DNI
    static public function tratarArchivo($archivoTrabajar,$nombre){ 
        
        
        if ($archivoTrabajar['size'] > 35840) {
            $nombreTrabajos = "error de size";
        }else{
            $tmp_name = $archivoTrabajar['tmp_name'];
            $nombreTratar = $nombre;
            $name = $nombre;
            $extension = $archivoTrabajar['name'];
            $extension = explode('.', $extension);
            $extension = $extension[1];
            //$nombre = $id.'_'.$name[0].time() . '.' . explode('.', $name)[1];  // Investigar explode
            $nombreTrabajos = $name.time() . '.' . $extension; 
            $folder = 'imagenes/';
            move_uploaded_file($tmp_name, $folder . $nombreTrabajos);
        }
        return $nombreTrabajos;
    }

    static public function moverArchivo($archivoTrabajar,$nombreImagen){
        
        if ($archivoTrabajar['size'] > 35840) {
            $banderaNombre = "error de size";
        }else{

            $origen = "imagenes/$nombreImagen";
            $destino = "backup/$nombreImagen";
            if (copy($origen, $destino))
                unlink($origen);

            $tmp_name = $archivoTrabajar['tmp_name'];
            //$name = $archivoTrabajar['name'];
            //$nombre = $id.'_'.$name[0].time() . '.' . explode('.', $name)[1];  // Investigar explode
            $nombre = $nombreImagen;
            $folder = 'imagenes/';
            move_uploaded_file($tmp_name, $folder . $nombre);
            $banderaNombre = $nombre;
        }
        return $banderaNombre;
    }

    static public function encliptarClave($clave){
        $claveATratar = $clave;
	    $salt = "f#@V)Hu^%Hgfds";
        $devolver_clave = sha1($salt.$claveATratar);
        return $devolver_clave;
    }
}