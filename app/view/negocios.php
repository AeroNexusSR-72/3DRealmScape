<?php
require_once '../model/usuario_m.php';
require_once '../controller/Usuario_c.php';

session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: ../view/login.php');
    exit;
}

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
    <link rel="stylesheet" href="../public/css/carta.css">
    <link rel="stylesheet" href="../public/css/carta_usuario.css">
    <link rel="stylesheet" href="../public/css/nav.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div id="contenido">
        <?php include '../public/js/nav.php'; ?>
        
        <div id="repertorio">
                <div id="usuarios">
                    <?php foreach ($usuarios as $usuario): ?>
                        <div class="user-card" onclick="window.location.href='contacto.php?id=<?php echo urlencode($usuario['id']);?>'">
                            <div class="user-image">
                                <img src="../data/img/<?php echo htmlspecialchars($usuario['imagen']); ?>" alt="<?php echo htmlspecialchars($usuario['n_perfil']); ?>">
                            </div>
                            <div class="user-info">
                                <h2><?php echo htmlspecialchars($usuario['n_perfil']); ?></h2>
                                <p>Seguidores: <!-- Puedes añadir aquí el número de seguidores, si lo tienes disponible --></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>   
        </div>
    </div>
    
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


