<?php
require_once '../model/usuario_m.php';
require_once '../controller/Usuario_c.php';

session_start(); // Iniciar la sesión

if (!isset($_SESSION['usuario'])) {
    header('Location: ../view/login.php');
    exit;
}

$id_contacto = $_SESSION['usuario']['id'];

$imagen = $_SESSION['usuario']['imagen'];

$modeloUsuario = new Usuario("../data/usuarios.json");
$controlUsuario = new UsuarioController($modeloUsuario);

// Obtener el usuario completo por ID
$usuario = $modeloUsuario->obtenerUsuarioPorId($id_contacto);
$correosUsuario = isset($usuario['correos']) ? $usuario['correos'] : [];


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Encargo</title>
    <link rel="stylesheet" href="../public/css/index.css">
    <link rel="stylesheet" href="../public/css/contacto.css">
    <link rel="stylesheet" href="../public/css/nav.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div id="contenido">
        <?php include '../public/js/nav.php'; ?>
        <div id="correos">
            <h3 style="margin-bottom:20px;">Correos:</h3>
            <?php if (!empty($correosUsuario)): ?>
                <?php foreach ($correosUsuario as $correo): ?>
                    <?php echo '<div class="correo-item">';?>
                        <?php echo '<div class="datos-correo">';?>
                            <h4>Encargo de: <?php echo htmlspecialchars($correo['usuario']); ?></h4>
                            <p><strong>Descripción:</strong> <?php echo htmlspecialchars($correo['descripcion']); ?></p>
                            <p><strong>Presupuesto:</strong> <?php echo htmlspecialchars("$".number_format($correo['presupuesto'],0,',','.')); ?></p>
                            <p><strong>Fecha Límite:</strong> <?php echo htmlspecialchars($correo['fecha']); ?></p>
                            <?php
                            // Fecha de envío en formato Y-m-d (como la que tienes en $correo['fecha'])
                            $fechaEnvio = new DateTime($correo['fecha']);

                            // Fecha actual
                            $fechaActual = new DateTime(date('Y-m-d'));

                            // Calcular la diferencia entre ambas fechas
                            $diferencia = $fechaEnvio->diff($fechaActual);

                            // Obtener el número de días
                            $diasEnviados = $diferencia->days;

                            echo "<p>".$diasEnviados . " días restantes</p>";
                            ?>
                        <?php echo '</div>';?>
                        <?php echo '<div class="boton">';?>
                            <button>Ver Detalles</button>
                            <button>Responder</button>
                            <button>Aceptar</button>
                            <button>Borrar</button>
                        <?php echo '</div>';?>
                        <?php echo '</div>';?>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No te han llegado correos aún.</p>
            <?php endif; ?>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

