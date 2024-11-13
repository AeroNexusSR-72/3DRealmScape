<?php

class Usuario {
    private $archivoJSON;

    public function __construct($archivoJSON) {
        $this->archivoJSON = $archivoJSON;
    }

    public function obtenerUsuarios() {
        $datosUsuarios = file_get_contents($this->archivoJSON);
        return json_decode($datosUsuarios, true);
    }

    public function agregarUsuario($datosUsuario) {
        $usuarios = $this->obtenerUsuarios();
        $usuarios[] = $datosUsuario;
        file_put_contents($this->archivoJSON, json_encode($usuarios, JSON_PRETTY_PRINT));
    }

    public function obtenerUsuarioPorEmail($email) {
        $usuarios = $this->obtenerUsuarios();
    
        if (!is_array($usuarios)) {
            return null;
        }
    
        foreach ($usuarios as $usuario) {
            if ($email == $usuario['email']) {
                return $usuario;
            }
        }
    
        return null;
    }

    public function guardarUsuarios($usuarios) {
        file_put_contents($this->archivoJSON, json_encode($usuarios, JSON_PRETTY_PRINT));
    }
}
?>
