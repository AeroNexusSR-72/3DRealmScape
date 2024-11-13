<?php
session_start();

// Inicializa el paso del registro
$step = isset($_POST['step']) ? (int)$_POST['step'] : 1;

// Manejo del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($step === 1) {
        // Guardar los datos del primer paso
        $_SESSION['nombre'] = htmlspecialchars(trim($_POST['nombre']));
        $_SESSION['email'] = htmlspecialchars(trim($_POST['email']));
        $_SESSION['n_perfil'] = htmlspecialchars(trim($_POST['n_perfil']));
        $_SESSION['password'] = trim($_POST['password']); // Almacena la contraseña en texto plano

        // Incrementa el paso
        $step = 2;
    } elseif ($step === 2) {
        // Guardar los datos del segundo paso
        $directorioDestino = '../data/img/';
        $nombreUsuario = preg_replace("/[^A-Za-z0-9]/", '', $_SESSION['n_perfil']); // Filtrar caracteres especiales
        $timestamp = time(); // Crear un timestamp único
        $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION); // Obtener la extensión de la imagen
        $nombreArchivo = "{$nombreUsuario}_{$timestamp}.{$extension}"; // Nombre final del archivo

        // Ruta completa de destino
        $rutaCompleta = $directorioDestino . $nombreArchivo;

        // Mover el archivo cargado al directorio de destino
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaCompleta)) {
            $_SESSION['imagen'] = $nombreArchivo; // Guardar solo el nombre del archivo en la sesión
        } else {
            echo "Error al subir la imagen";
            exit();
        }
        $_SESSION['telefono'] = htmlspecialchars(trim($_POST['telefono']));
        $_SESSION['descripcion'] = htmlspecialchars(trim($_POST['descripcion']));

        // Incluir el modelo y el controlador necesarios
        require_once '../model/usuario_m.php';
        require_once '../controller/usuario_c.php';

        // Obtener el ID más alto actual en el archivo JSON y sumarle 1
        $modeloUsuario = new Usuario("../data/usuarios.json");
        $controlUsuario = new UsuarioController($modeloUsuario);
        $usuarios = $controlUsuario->obtenerUsuarios();

        // Buscar el ID más alto
        $maxId = 0;
        foreach ($usuarios as $usuario) {
            if (isset($usuario['id']) && $usuario['id'] > $maxId) {
                $maxId = $usuario['id'];
            }
        }
        $nuevoId = $maxId + 1;

        // Prepara los datos del usuario con el nuevo ID
        $datosUsuario = [
            'id' => $nuevoId,
            'nombre' => $_SESSION['nombre'],
            'n_perfil' => $_SESSION['n_perfil'],
            'email' => $_SESSION['email'],
            'password' => $_SESSION['password'], // Aún en texto plano
            'telefono' => $_SESSION['telefono'], // Aún en texto plano
            'imagen' => $_SESSION['imagen'],
            'descripcion' => $_SESSION['descripcion'],
        ];

        // Agregar usuario a la base de datos
        $controlUsuario->agregarUsuario($datosUsuario);

        // Limpia la sesión y redirige
        session_destroy();
        header('Location: ../view/login.php');
        exit();
    }
}
?>

