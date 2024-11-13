<?php

class Modelo3DController {
    private $modelo3D;

    public function __construct($modelo3D) {
        $this->modelo3D = $modelo3D;
    }

    public function obtenerModelos() {
        return $this->modelo3D->obtenerModelos();
    }

    public function agregarModelo($datosModelo) {
        $this->modelo3D->agregarModelo($datosModelo);
    }

    public function incrementarDescargas($id) {
        $modelos = $this->modelo3D->obtenerModelos();
        foreach ($modelos as &$modelo) {
            if ($modelo['id'] == $id) {
                $modelo['descargas']++;
                break;
            }
        }
        $this->modelo3D->guardarModelos($modelos);
    }

    public function actualizarAutorPorUsuario($nombreAnterior, $nuevoNombre) {
        $modelos = $this->modelo3D->obtenerModelos();

        foreach ($modelos as &$modelo) {
            if ($modelo['creador'] === $nombreAnterior) {
                $modelo['creador'] = $nuevoNombre; // Actualiza el nombre del creador
            }
        }

        // Guardar los cambios en el archivo JSON
        $this->modelo3D->guardarModelos($modelos);
    }
}

?>
