<nav id="menu-1">
<div>
    <img src="../public/img/logo_completo.svg" alt="img">
</div>
<div id="cerrar-sesion">
    <nav class="nav">
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="../view/logout.php">Cerrar Sesión</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="../view/correo.php">Correo</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="../view/upload.php">Subir</a>
        </li>
        <li class="nav-item">
            <img id="img" src="../data/img/<?php echo htmlspecialchars($imagen); ?>" alt="img" width="40px" height="40px" onclick="window.location.href='user.php?id=<?php echo $id;?>'">
        </li>
    </nav>
</div>
</nav>

<nav id="menu-2">
<div>
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="../view/index.php">Modelos 3D</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="../view/negocios.php">Negocios</a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Explorar
            </a>
            <ul class="dropdown-menu">
                <?php
                $categorias = ['objetos', 'robot', 'escenario', 'arquitectura', 'vehiculos', 'personaje', 'criatura', 'animales', 'naturaleza', 'artefactos', 'edificios', 'maquinas', 'juguetes', 'alimentos', 'muebles', 'fantasia', 'historia', 'deporte', 'tecnologia'];
                foreach ($categorias as $categoria) {
                    echo "<li><a class='dropdown-item' href='index.php?categoria=$categoria'>$categoria</a></li>";
                }
                ?>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="../view/m_propios.php">Mis Modelos</a>
        </li>
        <!-- Formulario de búsqueda que envía la búsqueda automáticamente -->
        <li class="nav-item">
            <form class="d-flex" role="search" method="GET" action="index.php">
                <input class="form-control me-2" type="search" placeholder="Buscar" name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" aria-label="Search">
            </form>
        </li>
    </ul>
</div>
</nav>
