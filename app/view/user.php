<?php
require_once '../model/usuario_m.php';
require_once '../model/modelo3D_m.php';
require_once '../controller/Usuario_c.php';
require_once '../controller/Modelo3D_c.php';

//sesion
session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: ../view/login.php');
    exit;
}

//variables

$id = $_SESSION['usuario']['id'];
$nombre = $_SESSION['usuario']['nombre'];
$usuario = $_SESSION['usuario']['n_perfil'];
$email = $_SESSION['usuario']['email'];
$password = $_SESSION['usuario']['password'];
$telefono = $_SESSION['usuario']['telefono'];
$imagen = isset($_SESSION['usuario']['imagen']) ? $_SESSION['usuario']['imagen'] : 'default.png';
$descripcion = $_SESSION['usuario']['descripcion'];



// Manejar la actualización del perfil
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Llamar al controlador para actualizar el usuario
    $usuarioModel = new Usuario('../data/usuarios.json');
    $usuarioController = new UsuarioController($usuarioModel);
    $modelo3D = new Modelo3D("../data/modelos3D.json");
    $controlModelo3D = new Modelo3DController($modelo3D);

    $nuevosDatos = [
        'id' => $id,
        'nombre' => $_POST['nombre'],
        'n_perfil' => $_POST['perfil'],
        'email' => $_POST['email'],
        'telefono' => $_POST['telefono'],
        'descripcion' => $_POST['descripcion'],
        'imagen' => isset($_FILES['imagen']['name']) && $_FILES['imagen']['name'] ? $_FILES['imagen']['name'] : $imagen,
    ];

    // Procesar imagen de perfil
    if ($_FILES['imagen']['name']) {
        $nombreArchivoOriginal = $_SESSION['usuario']['imagen'];
        $rutaImagenOriginal = '../data/img/' . $nombreArchivoOriginal;
        $nuevoNombreArchivo = $_POST['perfil'] . '_' . basename($_FILES['imagen']['name']);
        $rutaDestino = '../data/img/' . $nuevoNombreArchivo;

        if ($_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            // Eliminar la imagen original si existe y no es la imagen predeterminada
            if ($nombreArchivoOriginal !== 'default.png' && file_exists($rutaImagenOriginal)) {
                unlink($rutaImagenOriginal);
            }

            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
                $_SESSION['usuario']['imagen'] = $nuevoNombreArchivo;
                $usuarioController->actualizarUsuario($id, ['imagen' => $nuevoNombreArchivo]);
            } else {
                echo "Error al subir la imagen.";
            }
        } else {
            echo "Error en la carga del archivo: " . $_FILES['imagen']['error'];
        }
    }


    if ($_POST['perfil'] !== $nombreAnterior) {
        $nombreAnterior = $_SESSION['usuario']['n_perfil'];
        // Actualiza el nombre del autor en los modelos creados por el usuario
        $controlModelo3D->actualizarAutorPorUsuario($nombreAnterior, $_POST['perfil']);
    }


    // Actualizar los datos de la sesión
    $_SESSION['usuario'] = $usuarioController->obtenerUsuarioPorId($id);

    // Redirigir para evitar resubmit del formulario
    header('Location: user.php');
    exit;
}

// Ruta de la imagen de perfil
$rutaImagenPerfil = "../data/img/" . $imagen;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <link rel="stylesheet" href="../public/css/nav.css">
    <link rel="stylesheet" href="../public/css/user.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../public/css/index.css">
</head>
<body>
    <div id="contenido">
        <!-- Menú de Navegación -->
        <?php include '../public/js/nav.php'; ?>

        <!-- Formulario Principal -->
        <div class="main-form">
            <!-- Columna Izquierda (Imagen de Perfil y Actividad) -->
            <div class="left-column">
                <div class="profile-section">
                    <p><?php echo $usuario?></p>
                    <img src="<?= htmlspecialchars($rutaImagenPerfil) ?>" alt="Imagen de perfil" class="upload-image">
                    <form action="user.php" method="POST" enctype="multipart/form-data">
                        <input type="file" name="imagen" id="upload" style="width:250px;">

                </div>
                    <div id="descripcion">
                        <div class="row">
                            <label style="display:flex; justify-content:center;">Descripción</label>
                            <textarea name="descripcion" id="descripcion"><?php echo $descripcion?></textarea>
                        </div>
                    </div>
            </div>

            <!-- Columna Derecha (Formulario de Datos) -->
            <div class="right-column">
                <h1>Configuración</h1>
                    <div class="row-container">
                        <div class="row">
                            <label>Perfil</label>
                            <input type="text" name="perfil" placeholder="nombre" value="<?php echo $usuario?>">
                        </div>
                        <div class="row">
                            <label>Nombre</label>
                            <input type="text" name="nombre" placeholder="nombre" value="<?php echo $nombre?>">
                        </div>
                    </div>
                    
                    <div class="row-container">
                        <div class="row">
                            <label>Telefono</label>
                            <input type="text" name="telefono" placeholder="Telefono" value="<?php echo $telefono?>">
                        </div>
                        <div class="row">
                            <label>Correo</label>
                            <input type="email" name="email" placeholder="nombre@example.com" value="<?php echo $email?>">
                        </div>
                    </div>
                    
                    <div class="row-container">
                        <div class="row">
                            <label>Contraseña</label>
                            <input type="password" id="password" placeholder="Contraseña" value="<?php echo $password; ?>">

                            <label class="check">
                                <input type="checkbox" onclick="document.getElementById('password').type = this.checked ? 'text' : 'password'">
                                <p>mostrar contraseña</p>
                            </label>
                        </div>
                        <div class="row">
                            <label>Validar Contraseña</label>
                            <input type="password" id="valid" placeholder="Contraseña" value="<?php echo $password; ?>">

                            <label class="check">
                                <input type="checkbox" onclick="document.getElementById('valid').type = this.checked ? 'text' : 'password'">
                                <p>mostrar contraseña</p>
                            </label>
                        </div>
                    </div>

                    <div class="buttons">
                        <button type="submit" class="save-btn">Save</button>
                        <button type="button" class="cancel-btn" onclick="window.location.href='user.php'">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybWoNB6DD0Oc3Ik6FJwSM9N/sftrYtPe9oswY89P8h4R1DGTY" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-7QbHglx34O+S7G9tJUk1p4TA7g/PVNKFIBT9tO45ofcbz4Zqf3yyT4YX7dyklR0s" crossorigin="anonymous"></script>
</body>
</html>
