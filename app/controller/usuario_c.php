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

    // Agregar función para manejar correos
    public function agregarCorreo($idUsuario, $usuario, $descripcion, $presupuesto) {
        // Ruta de los correos
        $rutaCorreos = "../data/correos.json";

        // Si el archivo de correos no existe, se crea
        if (!file_exists($rutaCorreos)) {
            $correos = [];
            file_put_contents($rutaCorreos, json_encode($correos, JSON_PRETTY_PRINT));
        } else {
            $correos = json_decode(file_get_contents($rutaCorreos), true);
        }

        // Verificar si ya existe una entrada para este usuario
        if (!isset($correos[$idUsuario])) {
            $correos[$idUsuario] = [];
        }

        // Agregar nuevo correo al usuario
        $correoNuevo = [
            'usuario' => $usuario,
            'descripcion' => $descripcion,
            'presupuesto' => $presupuesto
        ];

        // Agregar a la lista de correos del usuario
        $correos[$idUsuario][] = $correoNuevo;

        // Guardar los cambios en el archivo de correos
        file_put_contents($rutaCorreos, json_encode($correos, JSON_PRETTY_PRINT));
    }
}
?>
