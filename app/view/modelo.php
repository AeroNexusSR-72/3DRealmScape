<?php
require_once '../model/usuario_m.php';
require_once '../model/modelo3D_m.php';
require_once '../controller/Usuario_c.php';
require_once '../controller/Modelo3D_c.php';

session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../view/login.php');
    exit;
}

$usuario = $_SESSION['usuario'];
$id = $_SESSION['usuario']['id'];
$modelo3D = new Modelo3D("../data/modelos3D.json");
$controlModelo3D = new Modelo3DController($modelo3D);
$modelos = $controlModelo3D->obtenerModelos();

$modeloSeleccionado = null;
$modelo_id = isset($_GET['id']) ? (string) $_GET['id'] : null;
foreach ($modelos as $modelo) {
    if ($modelo['id'] == $modelo_id) {
        $modeloSeleccionado = $modelo;
        break;
    }
}

// Incrementa el contador de descargas
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['descargar']) && $modeloSeleccionado) {
    $controlModelo3D->incrementarDescargas($modelo_id);
    header("Location: " . $modeloSeleccionado['modelo3D']);
    exit;
}

$usuario_id = $_SESSION['usuario']['id'];
$imagen = isset($_SESSION['usuario']['imagen']) ? $_SESSION['usuario']['imagen'] : 'default.png';
$rutaImagenPerfil = "../data/img/" . $imagen;

// Procesar el formulario de comentarios
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && $modeloSeleccionado) {
    $comentario = htmlspecialchars($_POST['comentario']);
    $fecha = date('Y-m-d');
    $n_perfil = $usuario['n_perfil'];

    if (!isset($modeloSeleccionado['comentarios'])) {
        $modeloSeleccionado['comentarios'] = [
            'autor' => [],
            'fecha' => [],
            'texto' => []
        ];
    }

    $modeloSeleccionado['comentarios']['autor'][] = $n_perfil;
    $modeloSeleccionado['comentarios']['fecha'][] = $fecha;
    $modeloSeleccionado['comentarios']['texto'][] = $comentario;

    foreach ($modelos as &$modelo) {
        if ($modelo['id'] == $modelo_id) {
            $modelo['comentarios'] = $modeloSeleccionado['comentarios'];
            break;
        }
    }
    file_put_contents("../data/modelos3D.json", json_encode($modelos, JSON_PRETTY_PRINT));
}

// Borrar un comentario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['borrar_comentario']) && $modeloSeleccionado) {
    $index = (int)$_POST['index'];
    if (isset($modeloSeleccionado['comentarios']['autor'][$index])) {
        unset($modeloSeleccionado['comentarios']['autor'][$index]);
        unset($modeloSeleccionado['comentarios']['fecha'][$index]);
        unset($modeloSeleccionado['comentarios']['texto'][$index]);

        $modeloSeleccionado['comentarios']['autor'] = array_values($modeloSeleccionado['comentarios']['autor']);
        $modeloSeleccionado['comentarios']['fecha'] = array_values($modeloSeleccionado['comentarios']['fecha']);
        $modeloSeleccionado['comentarios']['texto'] = array_values($modeloSeleccionado['comentarios']['texto']);

        foreach ($modelos as &$modelo) {
            if ($modelo['id'] == $modelo_id) {
                $modelo['comentarios'] = $modeloSeleccionado['comentarios'];
                break;
            }
        }
        file_put_contents("../data/modelos3D.json", json_encode($modelos, JSON_PRETTY_PRINT));
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Modelo 3D</title>
    <link rel="stylesheet" href="../public/css/nav.css">
    <link rel="stylesheet" href="../public/css/modelo.css">
    <link rel="stylesheet" href="../public/css/index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paymentfont/1.1.1/paymentfont.min.css">
</head>
<body>
    <div id="contenido">
        <?php include '../public/js/nav.php'; ?>
        <div class="container mt-2">
            <div id="div_model" class="model-viewer-container">
                <model-viewer src="<?php echo htmlspecialchars($modeloSeleccionado['modelo3D']); ?>" alt="<?php echo htmlspecialchars($modeloSeleccionado['nombre']); ?>" auto-rotate camera-controls style="width: 100%; height:100%;" camera-orbit="45deg 80deg" scale="0.1 0.1 0.1"></model-viewer>
            </div>
            <div class="mt-4" style="display:flex; text-align: left; flex-direction:column;">
                <div style="display:flex;justify-content: space-between;">
                    <div id="left">
                        <h1><?php echo htmlspecialchars($modeloSeleccionado['nombre']); ?></h1><br>

                        <?php if($modeloSeleccionado['valor'] == "gratis"){?>
                                
                            <h3 class=""><strong>Precio:</strong> <?php echo htmlspecialchars('Gratis'); ?></h3>
                        
                        <?php }else if($modeloSeleccionado['valor'] != "gratis"){?>
                                
                            <h3 class=""><strong>Precio:</strong> <?php echo htmlspecialchars(number_format($modeloSeleccionado['valor'],0,'.',',').' COP'); ?></h3>
                        
                        <?php }?>

                        <p class="text-muted mt-4"><?php echo htmlspecialchars($modeloSeleccionado['descripcion']); ?></p>
                        <p><strong>Creador:</strong> <?php echo htmlspecialchars($modeloSeleccionado['creador']); ?></p>
                        <p><strong>Tipo:</strong> <?php echo implode(", ", $modeloSeleccionado['tipo']); ?></p>
                        <p><strong>Descargas:</strong> <?php echo htmlspecialchars($modeloSeleccionado['descargas']); ?></p>
                    </div>
                    <div>
                        <!-- Formulario de descarga -->
                        <form method="POST" action="">
                            <p class="text-muted">Descargas:<?php echo htmlspecialchars($modeloSeleccionado['descargas']); ?></p>
                            <?php if ($modeloSeleccionado['valor'] == "gratis"): ?>
                                <input type="hidden" name="descargar" value="1">
                                <button type="submit" class="btn btn-primary">Descargar</button>
                            <?php else: ?>
                                <!-- Botón para abrir el modal de pago -->
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalPago">
                                    Comprar
                                </button>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
                <h3 class="mt-2">Añadir un comentario</h3>
                <form method="post" action="" class="comment comment-form">
                    <label for="comentario">Comentario:</label>
                    <textarea name="comentario" id="comentario" required></textarea>
                    <button type="submit" name="submit">Añadir Comentario</button>
                </form>
                <h3 class="mt-2">Comentarios</h3>
                <?php if (!empty($modeloSeleccionado['comentarios']['autor'])): ?>
                    <?php for ($i = 0; $i < count($modeloSeleccionado['comentarios']['autor']); $i++): ?>
                        <div class="comment">
                            <div class="user-comment">
                                <p><strong><?php echo htmlspecialchars($modeloSeleccionado['comentarios']['autor'][$i]); ?></strong> (<?php echo htmlspecialchars($modeloSeleccionado['comentarios']['fecha'][$i]); ?>):</p>
                            </div>
                            <p><?php echo htmlspecialchars($modeloSeleccionado['comentarios']['texto'][$i]); ?></p>
                            <form method="post" action="">
                                <input type="hidden" name="index" value="<?php echo $i; ?>">
                                <?php if (($modeloSeleccionado['comentarios']['autor'][$i]) == $_SESSION['usuario']['n_perfil']) {?>

                                    <input type="submit" name="borrar_comentario" value="Borrar Comentario" class="btn btn-danger btn-sm">

                                <?php } ?>
                            </form>
                        </div>
                    <?php endfor; ?>
                <?php else: ?>
                    <p>No hay comentarios.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>


    <!-- Modal de Pago -->
<div class="modal fade" id="modalPago" tabindex="-1" aria-labelledby="modalPagoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalPagoLabel">Formulario de Pago</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
            <i class="pf pf-visa"></i>
            <i class="pf pf-mastercard"></i>
            <i class="pf pf-amex"></i>
            </div>
            <form method="POST" action="pagar.php">
                <div class="mb-3">
                    <label for="tarjeta" class="form-label"><i class="fas fa-credit-card"></i>
                        Número de tarjeta</label>
                    <input type="text" class="form-control" id="tarjeta" name="tarjeta" required>
                </div>
                <div class="mb-3">
                    <label for="fecha-expiracion" class="form-label"><i class="fas fa-calendar-alt"></i>
                        Fecha de expiración</label>
                    <input type="month" class="form-control" id="fecha-expiracion" name="fecha_expiracion" required>
                </div>
                <div class="mb-3">
                    <label for="cvv" class="form-label"><i class="fas fa-lock"></i> CVV</label>
                    <input type="text" class="form-control" id="cvv" name="cvv" required>
                </div>
                <button type="submit" class="btn btn-primary">Realizar Pago</button>
            </form>
        </div>
    </div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
