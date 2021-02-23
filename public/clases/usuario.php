<?php
namespace Clases;

class Usuario{
    public $email;
    public $clave;
    public $tipo;
    
    //public $fotoNombre;
    

    public function __construct($email,$clave,$tipo){

        $this->email = $email;
        $this->clave = $clave;
        $this->tipo = $tipo;
        //$this->fotoNombre = $fotoNombre;
    }

}