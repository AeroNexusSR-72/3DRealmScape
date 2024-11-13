<?php

class Modelo3D {
    private $archivoJSON;

    public function __construct($archivoJSON) {
        $this->archivoJSON = $archivoJSON;
    }

    public function obtenerModelos() {
        $datosModelos = file_get_contents($this->archivoJSON);
        return json_decode($datosModelos, true);
    }

    public function agregarModelo($datosModelo) {
        $modelos = $this->obtenerModelos();
        $modelos[] = $datosModelo;
        file_put_contents($this->archivoJSON, json_encode($modelos, JSON_PRETTY_PRINT));
    }

    public function guardarModelos($modelos) {
        file_put_contents($this->archivoJSON, json_encode($modelos, JSON_PRETTY_PRINT));
    }
}
?>

