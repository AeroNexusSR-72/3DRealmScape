<?php
session_start();

// Inicializa el paso del registro o usa el que se envió en POST
$step = $_POST['step'] ?? 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesa los datos según el paso en el que estamos
    switch ($step) {
        case 1:
            // Guardar los datos del primer paso
            $_SESSION['nombre'] = htmlspecialchars(trim($_POST['nombre']));
            $_SESSION['email'] = htmlspecialchars(trim($_POST['email']));
            $_SESSION['n_perfil'] = htmlspecialchars(trim($_POST['n_perfil']));
            $_SESSION['password'] = trim($_POST['password']); // Almacena la contraseña en texto plano

            // Cambiar al siguiente paso
            $step = 2;
            break;

        case 2:
            case 2:
                
                $directorioDestino = '../data/img/';
                $nombreUsuario = preg_replace("/[^A-Za-z0-9]/", '', $_SESSION['n_perfil']); // Filtrar caracteres especiales
                $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
                $nombreArchivo = "{$nombreUsuario}.{$extension}";
    
                $rutaCompleta = $directorioDestino . $nombreArchivo;

                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaCompleta)) {
                    $_SESSION['imagen'] = $nombreArchivo;
                } else {
                    echo "Error al subir la imagen";
                    exit();
                }
            
            // Guardar datos en la sesión y continuar con el registro
            $_SESSION['imagen'] = $nombreArchivo;
            $_SESSION['telefono'] = htmlspecialchars(trim($_POST['telefono']));
            $_SESSION['descripcion'] = htmlspecialchars(trim($_POST['descripcion']));
            // Cargar modelo y controlador
            require_once '../model/usuario_m.php';
            require_once '../controller/usuario_c.php';

            // Obtener el nuevo ID incrementado
            $modeloUsuario = new Usuario("../data/usuarios.json");
            $controlUsuario = new UsuarioController($modeloUsuario);
            $usuarios = $controlUsuario->obtenerUsuarios();
            $nuevoId = max(array_column($usuarios, 'id')) + 1;

            // Preparar los datos del usuario
            $datosUsuario = [
                'id' => $nuevoId,
                'nombre' => $_SESSION['nombre'],
                'n_perfil' => $_SESSION['n_perfil'],
                'email' => $_SESSION['email'],
                'password' => $_SESSION['password'],
                'telefono' => $_SESSION['telefono'],
                'imagen' => $_SESSION['imagen'],
                'descripcion' => $_SESSION['descripcion'],
                "seguidores" => 0,
            ];

            // Guardar el usuario y limpiar la sesión
            $controlUsuario->agregarUsuario($datosUsuario);
            session_destroy();

            // Redirigir al login
            header('Location: ../view/login.php');
            exit();

        default:
            echo "Paso no válido.";
            exit();
    }
}
?>

