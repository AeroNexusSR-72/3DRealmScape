<?php

require_once '../model/modelo3D_m.php';
require_once 'modelo3D_c.php';

session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: ../view/login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: ../view/index.php');
    exit;
}

$modelo3D = new Modelo3D("../data/modelos3D.json");
$controlModelo3D = new Modelo3DController($modelo3D);
$id = $_GET['id'];

// Incrementa el contador de descargas
$controlModelo3D->incrementarDescargas($id);

// seleccion del modelo especifico -> modeloSeleccionado
$modelos = $controlModelo3D->obtenerModelos();
$modeloSeleccionado = null;
foreach ($modelos as $modelo) {
    if ($modelo['id'] == $id) {
        $modeloSeleccionado = $modelo;
        break;
    }
}

// Verifica si el modelo existe y procede a la descarga
if (file_exists($modeloSeleccionado['modelo3D'])) {
    header('Content-Disposition: attachment; filename="' . basename($modeloSeleccionado['modelo3D']) . '"');
    readfile($modeloSeleccionado['modelo3D']);
    exit;
} else {
    echo "<p>El modelo 3D no se encuentra disponible.</p>";
}
?>
