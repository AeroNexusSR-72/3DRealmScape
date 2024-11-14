<nav id="menu-1">
<div>
    <img src="../public/img/logo_completo.svg" alt="img" width="120px">
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
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="../view/m_propios.php">Mis Modelos</a>
        </li>
    </ul>
</div>
</nav>