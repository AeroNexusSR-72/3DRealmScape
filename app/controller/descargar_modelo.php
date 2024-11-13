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

$id = $_GET['id'];

// Inicializa el modelo y el controlador
$modelo3D = new Modelo3D("../data/modelos3D.json");
$controlModelo3D = new Modelo3DController($modelo3D);

// Incrementa el contador de descargas
$controlModelo3D->incrementarDescargas($id);

// Obtiene el modelo específico
$modelos = $controlModelo3D->obtenerModelos();
$modeloSeleccionado = null;
foreach ($modelos as $modelo) {
    if ($modelo['id'] == $id) {
        $modeloSeleccionado = $modelo;
        break;
    }
}

// Verifica si el modelo existe y procede a la descarga
if ($modeloSeleccionado && file_exists($modeloSeleccionado['modelo3D'])) {
    $rutaArchivo = $modeloSeleccionado['modelo3D'];
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($rutaArchivo) . '"');
    header('Content-Length: ' . filesize($rutaArchivo));
    readfile($rutaArchivo);
    exit;
} else {
    echo "<p>El modelo 3D no se encuentra disponible.</p>";
}
?>
