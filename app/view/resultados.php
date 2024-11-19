<?php
session_start();
require_once '../model/modelo3D_m.php';
require_once '../controller/Modelo3D_c.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: ../view/login.php');
    exit;
}

// Obtén el término de búsqueda desde la sesión
$searchTerm = isset($_SESSION['searchTerm']) ? $_SESSION['searchTerm'] : '';

// Filtra los modelos según el término de búsqueda
$modelo3D = new Modelo3D("../data/modelos3D.json");
$controlModelo3D = new Modelo3DController($modelo3D);
$resultados = $controlModelo3D->buscarModelos($searchTerm);

// Inicializa los modelos y controladores
$modelo3D = new Modelo3D("../data/modelos3D.json");
$controlModelo3D = new Modelo3DController($modelo3D);

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
    <title>Resultados de Búsqueda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../public/css/index.css">
    <link rel="stylesheet" href="../public/css/carta.css">
    <link rel="stylesheet" href="../public/css/nav.css">
</head>
<body>
<div id="contenido">
    <?php include '../public/js/nav.php'; ?>
    <div id="saludo" class="text-center">
        <p>Resultados para "<strong><?php echo htmlspecialchars($searchTerm); ?></strong>"</p>
    </div>
    <div id="repertorio">
        
        <?php
        $chunkedCarruseles = array_chunk($resultados, 8); // Agrupa los productos en bloques de 8
        
        foreach ($chunkedCarruseles as $carruselIndex => $productosCarrusel) {
            echo "<div id='productosCarousel$carruselIndex' class='carousel slide' data-bs-ride='carousel'>";
            echo "<div class='carousel-inner'>";
            
            // Agrupa cada bloque de 8 productos en subgrupos de 4 para los slides internos
            $chunkedSlides = array_chunk($productosCarrusel, 4);
            
            foreach ($chunkedSlides as $slideIndex => $productosSlide) {
                $isActive = $slideIndex === 0 ? 'active' : ''; // Activa solo el primer slide
                echo "<div class='carousel-item $isActive'>";
                echo "<div class='row'>"; // Contenedor de productos dentro del slide
                
                foreach ($productosSlide as $producto) {
                    ?>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-2" style="display:flex;justify-content:center;">
                        <div class="product-card" onclick="window.location.href='modelo.php?id=<?php echo urlencode($producto['id']);?>'">
                            <div class="product-image">
                                <model-viewer src="<?php echo htmlspecialchars($producto['modelo3D']); ?>"
                                            alt="<?php echo htmlspecialchars($producto['nombre']); ?>"
                                            auto-rotate rotation-per-second="30deg">
                                </model-viewer>
                            </div>
                            <div class="product-info text-center">
                                <img src="../public/img/logo.svg" alt="" width="29px">
                                <h2><?php echo htmlspecialchars($producto['nombre']); ?></h2>
                                <p>
                                    <?php if ($producto['valor'] != "gratis") {
                                        echo htmlspecialchars("$".$producto['valor']);
                                    } else {
                                        echo htmlspecialchars($producto['valor']); 
                                    }?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php
                }

                echo "</div></div>"; // Cierra el contenedor del slide
            }

            echo "</div>";
            ?>
            
            <!-- Controles del carrusel -->
            <button class="carousel-control-prev" type="button" data-bs-target="#productosCarousel<?php echo $carruselIndex; ?>" data-bs-slide="prev" style="width:40px;">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#productosCarousel<?php echo $carruselIndex; ?>" data-bs-slide="next" style="width:40px;">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
            
            <?php
            echo "</div>"; // Cierra cada carrusel
        }
        ?>
    </div>

</div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybWoNB6DD0Oc3Ik6FJwSM9N/sftrYtPe9oswY89P8h4R1DGTY" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-7QbHglx34O+S7G9tJUk1p4TA7g/PVNKFIBT9o45ofcbz4Zqf3yyT4YX7dyklR0s" crossorigin="anonymous"></script>
    <script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>
</body>
</html>

