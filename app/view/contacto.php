<?php
require_once '../model/usuario_m.php';
require_once '../controller/Usuario_c.php';

session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: ../view/login.php');
    exit;
}

$id_contacto = $_GET['id'];

$modeloUsuario = new Usuario("../data/usuarios.json");
$controlUsuario = new UsuarioController($modeloUsuario);
$usuarios = $controlUsuario->obtenerUsuarios();

$usuario = $_SESSION['usuario'];
$id = $_SESSION['usuario']['id'];
$imagen = isset($_SESSION['usuario']['imagen']) ? $_SESSION['usuario']['imagen'] : 'default.png';
$rutaImagenPerfil = "../data/img/" . $imagen;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios</title>
    <link rel="stylesheet" href="../public/css/index.css">
    <link rel="stylesheet" href="../public/css/contacto.css">
    <link rel="stylesheet" href="../public/css/nav.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div id="contenido">
        <?php include '../public/js/nav.php'; ?>
        
        <div id="repertorio">
                <div id="usuarios">
                    <?php foreach ($usuarios as $usuario): 
                        if($usuario['id'] == $id_contacto){ ?>
                            <div id="contacto">
                                <div id="contact-descrip">
                                    <img src="../data/img/<?php echo htmlspecialchars($usuario['imagen']); ?>" alt="<?php echo htmlspecialchars($usuario['n_perfil']); ?>">
                                    <div>
                                        <h1>usuario</h1>
                                        <table id="tabla">
                                            <tbody>
                                                <tr>
                                                    <th>Usuario</th>
                                                    <td>usuario1</td>
                                                </tr>
                                                <tr>
                                                    <th>Nombre</th>
                                                    <td>Juan Andrés</td>
                                                </tr>
                                                <tr>
                                                    <th>Número</th>
                                                    <td>3006408720</td>
                                                </tr>
                                                <tr>
                                                    <th>Correo</th>
                                                    <td>juan@example.com</td>
                                                </tr>
                                                <tr>
                                                    <th>Seguidores</th>
                                                    <td>50</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="form-contact">
                                    <h2>Formulario</h2>
                                    <form action="" class="form">
                                    <div class="input-form">
                                        <label for="">Nombre:</label>
                                        <input type="text">

                                        <label for="">Correo:</label>
                                        <input type="email">

                                        <label for="">Descripcion:</label>
                                        <textarea name="" id="descrip">a</textarea>

                                        <button type="submit">enviar</button>
                                    </div>
                                    </form>
                                </div>
                            </div>
                    <?php } endforeach; ?>
                </div>   
        </div>
    </div>
    
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>