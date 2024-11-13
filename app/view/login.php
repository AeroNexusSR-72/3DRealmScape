<?php
session_start(); // Iniciar la sesión

require_once '../model/usuario_m.php';
require_once '../controller/usuario_c.php';

$modeloUsuario = new Usuario("../data/usuarios.json");
$controlUsuario = new UsuarioController($modeloUsuario);

$error = ""; // Variable para almacenar los mensajes de error

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars(trim($_POST['email']));
    $password = trim($_POST['password']);

    // Buscar usuario por email
    $usuario = $modeloUsuario->obtenerUsuarioPorEmail($email);

    // Verificar si existe el usuario y si la contraseña coincide
    if ($usuario && $usuario['password'] === $password) {
        $_SESSION['usuario'] = $usuario;
        header('Location: ../view/index.php');
    } else {
        $error = "Usuario o contraseña inválidos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="../public/css/login.css">
</head>
<body>
    <div id="contenido">
        <h1>Iniciar Sesión</h1>
        <form action="" method="POST" id="form">
            <div class="input-group">
                <label for="email">Email:</label>
                <div class="img-input">
                    <img src="../public/img/usuario.svg" alt="Usuario Icono">
                    <input type="email" id="email" name="email" required placeholder="Ingrese Correo" 
                           value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
                </div>
            </div>
            <div class="input-group">
                <label for="password">Contraseña:</label>
                <div class="img-input">
                    <img src="../public/img/candado.svg" alt="Candado Icono">
                    <input type="password" id="password" name="password" required placeholder="Ingrese Contraseña">
                </div>
            </div>
            <button type="submit">Iniciar Sesión</button>
        </form>
        <p>No tiene cuenta? <a href="../view/registro.php">Crea una ahora</a></p>
        
        <?php if (!empty($error)): ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>

