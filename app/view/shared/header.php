<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mundialist - Infografías</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<div class="menu-overlay" id="menuOverlay"></div>

<header class="main-header">
    <div class="nav-container">
        <div class="header-left">
            <?php if(isset($_SESSION['user'])): ?>
                <button class="btn-menu" id="openMenu">
                    <img src="resources/menu.png" alt="menu">
                </button>
            <?php endif; ?>
            
            <a href="index.php?action=mundiales" class="logo">
                Mundial<span>Infog</span>
            </a>
            
        </div>

        <div class="contenedor-busqueda">
            <select id="tipoBusqueda" class="busqueda-select">
                <option value="categoria">Categoría</option>
                <option value="anio">Año</option>
                <option value="pais">País Sede</option>
                <option value="usuario">Usuario</option>
            </select>
            
            <input type="text" id="terminoBusqueda" class="busqueda-input" placeholder="Escribe aquí para buscar...">
            
            <button id="btnBuscar" class="busqueda-btn">Buscar</button>
        </div>

        <nav class="nav-menu">
            <?php if(isset($_SESSION['user'])): ?>
                <div class="user-actions">
                    <div class="user-info">
                        <a href="index.php?action=perfil" class="profile-link">
                            <?php if($_SESSION['user']['foto']): ?>
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($_SESSION['user']['foto']); ?>" class="nav-avatar">
                            <?php else: ?>
                                <div class="nav-avatar-placeholder"><?php echo substr($_SESSION['user']['nombre'], 0, 1); ?></div>
                            <?php endif; ?>
                            <span class="user-name"><?php echo $_SESSION['user']['nombre']; ?></span>
                        </a>
                    </div>
                </div>

                <div class="mobile-sidebar" id="mobileSidebar">
                    <div class="sidebar-header">
                        <h3>Menú</h3>
                        <button class="btn-close" id="closeMenu">&times;</button>
                    </div>
                    <div class="sidebar-links">
                        <?php if($_SESSION['user']['tipoUsuario'] == 2): ?>
                            <p class="section-title">Administración</p>
                            <a href="index.php?action=admin_mundiales" class="sidebar-link"><span>+</span> Nuevo Mundial</a>
                            <a href="index.php?action=admin_categorias" class="sidebar-link">Categorías</a>
                            <a href="index.php?action=admin_publicaciones" class="sidebar-link">Aprobar Publicaciones</a>
                            <hr class="sidebar-divider">
                        <?php endif; ?>
                        <a href="index.php?action=perfil" class="sidebar-link">Mi Perfil</a>
                        <a href="index.php?action=logout" class="sidebar-link btn-logout-sidebar">Cerrar Sesión</a>
                    </div>
                </div>

            <?php else: ?>
                <div class="auth-buttons">
                    <a href="index.php?action=login" class="link-login">Entrar</a>
                    <a href="index.php?action=register" class="btn-register-nav">Unirse</a>
                </div>
            <?php endif; ?>
        </nav>
    </div>
</header>

<main class="page-content">

<script>
    const openBtn = document.getElementById('openMenu');
    const closeBtn = document.getElementById('closeMenu');
    const sidebar = document.getElementById('mobileSidebar');
    const overlay = document.getElementById('menuOverlay');

    if(openBtn) {
        openBtn.addEventListener('click', () => {
            sidebar.classList.add('active');
            overlay.classList.add('active');
        });
    }

    if(closeBtn) {
        const closeMenu = () => {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        };
        closeBtn.addEventListener('click', closeMenu);
        overlay.addEventListener('click', closeMenu);
    }
</script>