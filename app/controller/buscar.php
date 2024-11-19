<?php
session_start();
require_once '../model/modelo3D_m.php';
require_once '../controller/modelo3D_c.php';

if (!empty($_POST['search'])) {
    $searchTerm = strtolower(trim($_POST['search']));

    // Obtener modelos y filtrar resultados por nombre
    $modelo3DController = new Modelo3DController(new Modelo3D('../data/modelos3D.json'));
    $modelos = $modelo3DController->obtenerModelos();
    $resultados = array_filter($modelos, fn($m) => 
        str_contains(strtolower($m['nombre']), $searchTerm)
    );

    $_SESSION['searchTerm'] = $searchTerm; // Guardar término en sesión
    header('Location: ../view/resultados.php');
    exit;
}

echo "<p>No se ha ingresado un término de búsqueda.</p>";
?>



