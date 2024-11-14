<?php
require_once '../model/usuario_m.php';
require_once '../controller/Usuario_c.php';

session_start(); // Iniciar la sesión

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
        
                <div id="usuarios">
                    <?php foreach ($usuarios as $usuario): 
                        if($usuario['id'] == $id_contacto){ ?>
                            <div id="contacto">
                                <div id="contact-descrip">
                                    <img src="../data/img/<?php echo htmlspecialchars($usuario['imagen']); ?>" alt="<?php echo htmlspecialchars($usuario['n_perfil']); ?>">
                                    <div>
                                        <h1><?php echo $usuario['n_perfil'] ?></h1>
                                        <table id="tabla">
                                            <tbody>
                                                <tr>
                                                    <th>Nombre</th>
                                                    <td><?php echo $usuario['nombre'] ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Número</th>
                                                    <td><?php echo $usuario['telefono'] ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Correo</th>
                                                    <td><?php echo $usuario['email'] ?></td>
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
                                    <h2>Formulario de Encargo</h2>
                                    <form action="../controller/enviar_correo.php" method="POST" class="form">
                                        <div class="input-form">
                                            <label for="nombre">Perfil:</label>
                                            <input type="text" name="nombre" value="<?php echo htmlspecialchars($_SESSION['usuario']['n_perfil']); ?>" required>

                                            <!-- Correo del remitente, visible para el usuario que envía el encargo -->
                                            <label for="email">Tu Correo:</label>
                                            <input type="email" name="email" value="<?php echo htmlspecialchars($_SESSION['usuario']['email']); ?>" required>

                                            <!-- Campo oculto con el correo del freelancer como destinatario -->
                                            <input type="hidden" name="destinatario" value="<?php echo htmlspecialchars($usuario['email']); ?>">

                                            <label for="descripcion">Descripción del Encargo:</label>
                                            <textarea name="descripcion" id="descrip" required></textarea>

                                            <label for="presupuesto">Presupuesto Estimado:</label>
                                            <input type="number" name="presupuesto" min="0" required>

                                            <label for="fecha">Fecha Límite:</label>
                                            <input type="date" name="fecha" required>

                                            <button type="submit">Enviar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                    <?php } endforeach; ?>
                </div>
    </div>
    
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>