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
            <img id="img" src="../data/img/<?php echo htmlspecialchars($imagen); ?>" alt="img" width="40px" height="40px"onclick="window.location.href='user.php?id=<?php echo $id;?>'">
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
            <li><a class="dropdown-item" href="#">Objetos</a></li>
            <li><a class="dropdown-item" href="#">Robot</a></li>
            <li><a class="dropdown-item" href="#">Escenario</a></li>
            <li><a class="dropdown-item" href="#">Arquitectura</a></li>
            <li><a class="dropdown-item" href="#">Vehículos</a></li>
            <li><a class="dropdown-item" href="#">Personaje</a></li>
            <li><a class="dropdown-item" href="#">Criatura</a></li>
            <li><a class="dropdown-item" href="#">Animales</a></li>
            <li><a class="dropdown-item" href="#">Naturaleza</a></li>
            <li><a class="dropdown-item" href="#">Artefactos</a></li>
            <li><a class="dropdown-item" href="#">Edificios</a></li>
            <li><a class="dropdown-item" href="#">Máquinas</a></li>
            <li><a class="dropdown-item" href="#">Juguetes</a></li>
            <li><a class="dropdown-item" href="#">Alimentos</a></li>
            <li><a class="dropdown-item" href="#">Muebles</a></li>
            <li><a class="dropdown-item" href="#">Fantasía</a></li>
            <li><a class="dropdown-item" href="#">Historia</a></li>
            <li><a class="dropdown-item" href="#">Deporte</a></li>
            <li><a class="dropdown-item" href="#">Tecnología</a></li>
          </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="../view/m_propios.php">Mis Modelos</a>
        </li>
        <li class="nav-item">
            <form class="d-flex" method="POST" action="../controller/buscar.php">
                <input class="form-control me-2" type="search" name="search" placeholder="Buscar" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
            <ul id="results" class="list-group mt-3"></ul>
        </li>
    </ul>
</div>
</nav>