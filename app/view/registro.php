<?php
// Llama al controlador para gestionar la lógica del registro
require_once '../controller/usuario_v.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="../public/css/login.css">
</head>
<body>
    <div id="contenido">
        <?php if ($step === 1): ?>
            <h1>Registro de Usuario</h1>
            <form action="" method="POST" id="form">
                <input type="hidden" name="step" value="1">
                <label for="n_perfil">Nombre de Perfil:</label>
                <div class="img-input">
                    <input type="text" id="n_perfil" name="n_perfil" required>
                </div>
                <label for="nombre">Nombre:</label>
                <div class="img-input">
                    <input type="text" id="nombre" name="nombre" required>
                </div>
                <br>
                <label for="email">Email:</label>
                <div class="img-input">
                    <input type="email" id="email" name="email" required>
                </div>
                <br>
                <label for="password">Contraseña:</label>
                <div class="img-input">
                    <input type="password" id="password" name="password" required>
                </div>
                <br>
                <button type="submit">Siguiente</button>
            </form>
        <?php elseif ($step === 2): ?>
            <h1>Registro de Usuario</h1>
            <form action="" method="POST" enctype="multipart/form-data" id="form">
                <input type="hidden" name="step" value="2">
                <label for="imagen">Imagen de Perfil:</label>
                <div class="img-input">
                    <input type="file" id="imagen" name="imagen" required>
                </div>
                <label for="telefono">Telefono:</label>
                <div class="img-input">
                    <input type="tel" id="telefono" name="telefono">
                </div>
                <br>
                <label for="preferencias">Descripción:</label>
                <div class="img-input">
                    <textarea id="preferencias" name="preferencias"></textarea>
                </div>
                <br>
                <button type="submit">Registrar</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>