<?php
require_once '../model/modelo3D_m.php';
session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: ../view/login.php');
    exit;
}

// Obtener la información del usuario creador del modelo desde la sesión
$usuario = $_SESSION['usuario'];
$creador = $usuario['n_perfil'];

// Crear una instancia del modelo3D
$modelo3D = new Modelo3D("../data/modelos3D.json");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si el archivo se ha cargado correctamente
    if (isset($_FILES['modelo']) && $_FILES['modelo']['error'] == 0) {
        $fileTmpPath = $_FILES['modelo']['tmp_name'];
        $fileName = $_FILES['modelo']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Verificar que el archivo sea en formato .gbl
        if ($fileExtension == 'glb') {
            $destino = "../data/3D/" . $fileName;
            move_uploaded_file($fileTmpPath, $destino);

            // Obtener el último ID del archivo JSON
            $modelos = $modelo3D->obtenerModelos();
            $ultimoId = 0; // Inicializamos el último ID

            // Si hay modelos, buscamos el último ID
            if (!empty($modelos)) {
                // Extraemos todos los IDs y encontramos el máximo
                $ids = array_column($modelos, 'id');
                $ultimoId = max($ids);
            }

            // Crear el nuevo modelo en formato de arreglo
            $nuevoModelo = [
                "id" => $ultimoId + 1, // Asignamos el siguiente ID
                "nombre" => $_POST['nombre'],
                "descripcion" => $_POST['descripcion'],
                "modelo3D" => $destino,
                "fecha" => date("Y-m-d"),
                "valor" => $_POST['valor'] == 'gratis' ? 'gratis' : $_POST['precio'],
                "creador" => $creador,
                "tipo" => [$_POST['tipo']],
                "comentarios" => [
                    "fecha" => [],
                    "autor" => [],
                    "texto" => []
                ],
                "descargas" => 0
            ];

            // Agregar el nuevo modelo usando el método `agregarModelo` de la clase `Modelo3D`
            $modelo3D->agregarModelo($nuevoModelo);

            header('Location: ../view/index.php');
        } else {
            echo "El archivo debe estar en formato .gbl";
            header('Location: ../view/upload.php');
        }
    } else {
        echo "Error al subir el archivo.";
        header('Location: ../view/upload.php');
    }
}
$usuario = $_SESSION['usuario']; // Acceder a la información del usuario almacenada
$id = $_SESSION['usuario']['id'];
$imagen = isset($_SESSION['usuario']['imagen']) ? $_SESSION['usuario']['imagen'] : 'default.png';
$rutaImagenPerfil = "../data/img/" . $imagen;
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <link rel="stylesheet" href="../public/css/index.css">
    <link rel="stylesheet" href="../public/css/nav.css">
    <link rel="stylesheet" href="../public/css/user.css">
    <link rel="stylesheet" href="../public/css/upload.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div id="contenido">
        <?php include '../public/js/nav.php'; ?>
        <div style="display:flex;justify-content:center;align-items: center; height:auto;margin-top:40px;margin-bottom:40px;">
        <form method="post" enctype="multipart/form-data" class="subir">
            <!-- Campo para subir el archivo 3D -->
            <label for="modelo">Subir archivo</label>
            <input type="file" name="modelo" id="modelo">

            <!-- Campo para nombre del modelo -->
            <label for="nombre">Nombre del modelo</label>
            <input type="text" name="nombre" id="nombre" required>

            <!-- Campo para descripción del modelo -->
            <label for="descripcion">Descripción</label>
            <textarea name="descripcion" id="descripcion" rows="4" required placeholder="ingresa la descripcion del modelo"></textarea>

            <label for="valor">Valor</label>
            <select name="valor" id="valor" onchange="togglePrecio()" required>
                <option value="gratis" selected>Gratis</option>
                <option value="pago">Pago</option>
                <!-- Agrega más opciones según sea necesario -->
            </select>

            <!-- Campo para el precio, oculto inicialmente -->
            <div id="precio-container" style="display: none;flex-direction:column;">
                <label for="precio">Precio</label>
                <input type="number" name="precio" id="precio" min="0" step="0.01">
            </div>

            <script>
                function togglePrecio() {
                    const selectValor = document.getElementById("valor");
                    const precioContainer = document.getElementById("precio-container");

                    if (selectValor.value === "pago") {
                        precioContainer.style.display = "flex";  // Muestra el campo de precio
                    } else {
                        precioContainer.style.display = "none";   // Oculta el campo de precio
                    }
                }
            </script>

            <!-- Lista desplegable para tipo del modelo -->
            <label for="tipo">Tipo</label>
            <select name="tipo" id="tipo" required>
                <option value="objetos" selected>Objetos</option>
                <option value="robot">Robot</option>
                <option value="escenario">Escenario</option>
                <option value="arquitectura">Arquitectura</option>
                <option value="vehiculos">Vehículos</option>
                <option value="personaje">Personaje</option>
                <option value="criatura">Criatura</option>
                <option value="animales">Animales</option>
                <option value="naturaleza">Naturaleza</option>
                <option value="artefactos">Artefactos</option>
                <option value="edificios">Edificios</option>
                <option value="maquinas">Máquinas</option>
                <option value="juguetes">Juguetes</option>
                <option value="alimentos">Alimentos</option>
                <option value="muebles">Muebles</option>
                <option value="fantasia">Fantasía</option>
                <option value="historia">Historia</option>
                <option value="deporte">Deporte</option>
                <option value="tecnologia">Tecnología</option>
            </select>


            <!-- Botón de envío del formulario -->
            <button type="submit">Guardar modelo</button>
        </form>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybWoNB6DD0Oc3Ik6FJwSM9N/sftrYtPe9oswY89P8h4R1DGTY" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-7QbHglx34O+S7G9tJUk1p4TA7g/PVNKFIBT9tO45ofcbz4Zqf3yyT4YX7dyklR0s" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>
</html>