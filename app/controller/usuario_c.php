<?php
class UsuarioController {
    private $modeloUsuario;

    public function __construct($modeloUsuario) {
        $this->modeloUsuario = $modeloUsuario;
    }

    public function obtenerUsuarios() {
        return $this->modeloUsuario->obtenerUsuarios();
    }

    public function agregarUsuario($datosUsuario) {
        $this->modeloUsuario->agregarUsuario($datosUsuario);
    }

    // public function autenticarUsuario($email, $password) {
    //     $usuario = $this->modeloUsuario->obtenerUsuarioPorEmail($email);
    //     if ($usuario && password_verify($password, $usuario['password'])) {
    //         return $usuario; // Usuario autenticado
    //     }
    //     return null; // Credenciales inválidas
    // }

    public function actualizarUsuario($id, $nuevosDatos) {
        $usuarios = $this->modeloUsuario->obtenerUsuarios();
        foreach ($usuarios as &$usuario) {
            if ($usuario['id'] == $id) {
                $usuario = array_merge($usuario, $nuevosDatos);
                break;
            }
        }
        $this->modeloUsuario->guardarUsuarios($usuarios);
    }

    public function obtenerUsuarioPorId($id) {
        $usuarios = $this->modeloUsuario->obtenerUsuarios();
        foreach ($usuarios as $usuario) {
            if ($usuario['id'] == $id) {
                return $usuario;
            }
        }
        return null;
    }
}
?>
