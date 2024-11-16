<?php
class Usuario {
    private $archivo;

    public function __construct($archivo) {
        $this->archivo = $archivo;
    }

    // Obtener todos los usuarios desde el archivo JSON
    public function obtenerUsuarios() {
        if (!file_exists($this->archivo)) {
            return [];
        }

        $usuariosJson = file_get_contents($this->archivo);
        return json_decode($usuariosJson, true);
    }

    // Guardar todos los usuarios en el archivo JSON
    public function guardarUsuarios($usuarios) {
        file_put_contents($this->archivo, json_encode($usuarios, JSON_PRETTY_PRINT));
    }

    // Obtener un usuario por ID
    public function obtenerUsuarioPorId($id) {
        $usuarios = $this->obtenerUsuarios();
        foreach ($usuarios as $usuario) {
            if ($usuario['id'] == $id) {
                return $usuario;
            }
        }
        return null;
    }

    public function obtenerUsuarioPorEmail($email) {
        $usuarios = $this->obtenerUsuarios();

        foreach ($usuarios as $usuario) {
            if ($usuario['email'] === $email) {
                return $usuario;
            }
        }

        return null; // Si no encuentra el correo
    }

    // Función para agregar correos (si es necesario)
    public function agregarCorreo($id, $nombre, $descripcion, $presupuesto) {
        $usuarios = $this->obtenerUsuarios();
        foreach ($usuarios as &$usuario) {
            if ($usuario['id'] == $id) {
                $nuevoCorreo = [
                    'usuario' => $nombre,
                    'descripcion' => $descripcion,
                    'presupuesto' => $presupuesto,
                    'fecha' => date("Y-m-d")
                ];
                $usuario['correos'][] = $nuevoCorreo;
                break;
            }
        }

        // Guardar los usuarios actualizados en el archivo
        $this->guardarUsuarios($usuarios);
    }
}
?>

