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
                        <div class="container-btn-sidebar">
                            <button class="btn-search" id="btnSearch"><img src="resources/search.png" alt=""></button>
                            <button class="btn-close" id="closeMenu">&times;</button>
                        </div>
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
        <div class="overlay-search" id="overlaySearch">
            <div class="card-overlay-search" id="cardOverlay">
                <h2>Búsqueda</h2>
                <div class="container-overlay-input-search">
                    
                    
                    <input type="text" id="terminoBusquedaOverlay" class="busqueda-input-overlay" placeholder="Escribe aquí para buscar...">
                    
                    <button id="btnBuscarOverlay" class="busqueda-btn-overlay">Buscar</button>
                </div>
                <select id="tipoBusquedaOverlay" class="busqueda-select-overlay">
                        <option value="categoria">Categoría</option>
                        <option value="anio">Año</option>
                        <option value="pais">País Sede</option>
                        <option value="usuario">Usuario</option>
                </select>
            </div>
        </div>

    </div>

</header>

<main class="page-content" id="pageContent">
<script>
    document.addEventListener('DOMContentLoaded', ()=>{
        const btnBuscar = document.getElementById('btnBuscar');
        const selectBuscar = document.getElementById('tipoBusqueda');
        const terminoBusqueda = document.getElementById('terminoBusqueda');
        const btnSearch = document.getElementById('btnSearch');
        const overlaySearch = document.getElementById('overlaySearch');
        const btnBuscarOverlay = document.getElementById('btnBuscarOverlay');
        const terminoBusquedaOverlay = document.getElementById('terminoBusquedaOverlay');
        const selectBuscarOverlay = document.getElementById('tipoBusquedaOverlay');
        const cardOverlay = document.getElementById('cardOverlay');
        btnSearch.addEventListener('click', () =>{
            overlaySearch.style.display = 'flex';
        })

        if(overlaySearch){
            const closeSearch = () =>{
                console.log('hola')
                overlaySearch.style.display = 'none';

            }
            overlaySearch.addEventListener('click', closeSearch);
            cardOverlay.addEventListener('click', (e) => {

                e.stopPropagation();

            });
        }

        btnBuscarOverlay.addEventListener('click', () => {
            const valorSelectBuscarOverlay = selectBuscarOverlay.value;
            const valorTerminoBusquedaOverlay = terminoBusquedaOverlay.value;
            fetch('index.php?action=api_get_search', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ tipoBusqueda: valorSelectBuscarOverlay, termino: valorTerminoBusquedaOverlay })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    
                    const mainPage = document.getElementById('pageContent');
                    
                    mainPage.innerHTML = data.data.length ? '<div class="foro-container"><div id="feed-publicaciones" class="feed-section"></div></div>' : '<div class="foro-container"><div id="feed-publicaciones" class="feed-section"><p class="empty">No se han encontrado coincidencias.</p></div></div>';
                    const feed = document.getElementById('feed-publicaciones');
                    data.data.forEach(p => {
                        // Verificamos si es video o imagen basado en el mimeType
                        let multimediaHtml = '';
                        if (p.tipoPublicacion === 1) {
                        multimediaHtml = `<video src="data:video/mp4;base64,${p.multimedia}" class="post-img" controls></video>`;
                        } else {
                            multimediaHtml = `<img src="data:image/*;base64,${p.multimedia}" class="post-img">`;
                        }

                        feed.innerHTML += `
                            <article class="post-card card">
                                <div class="post-header">
                                    <img src="data:image/*;base64,${p.fotoUsuario}" class="user-avatar">
                                    <div>
                                        <h3>${p.nombreUsuario} ${p.apellidoUsuario}</h3>
                                        <span>${p.nombreCategoria} • ${new Date(p.fechaCreacion).toLocaleDateString()}</span>
                                    </div>
                                </div>
                                <p class="post-desc">${p.descripcion}</p>
                                ${multimediaHtml} 
                                
                                <div class="post-footer">
                                    <button class="btn-like" data-id=${p.idPublicacion}>❤️ <span id="like-count-${p.idPublicacion}">${p.likes}</span> Likes</button>
                                    <a class="btn-comment" href="index.php?action=publicacion&id=${p.idPublicacion}">💬 ${p.comentarios} Comentarios</a>
                                </div>
                                
                            </article>
                        `;
                    });
                    const sidebarInSearch = document.getElementById('mobileSidebar');
                    const overlayInSearch = document.getElementById('menuOverlay');
                    sidebarInSearch.classList.remove('active');
                    overlayInSearch.classList.remove('active');

                    overlaySearch.style.display = 'none';


                    
                    
                } else{
                    
                    alert("Error: " + data.mensaje);
                }
            });
        })

        btnBuscar.addEventListener('click', () => {
            const valorSelectBuscar = selectBuscar.value;
            const valorTerminoBusqueda = terminoBusqueda.value;
            fetch('index.php?action=api_get_search', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ tipoBusqueda: valorSelectBuscar, termino: valorTerminoBusqueda })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    
                    const mainPage = document.getElementById('pageContent');
                    
                    mainPage.innerHTML = data.data.length ? '<div class="foro-container"><div id="feed-publicaciones" class="feed-section"></div></div>' : '<div class="foro-container"><div id="feed-publicaciones" class="feed-section"><p class="empty">No se han encontrado coincidencias.</p></div></div>';
                    const feed = document.getElementById('feed-publicaciones');
                    data.data.forEach(p => {
                        // Verificamos si es video o imagen basado en el mimeType
                        let multimediaHtml = '';
                        if (p.tipoPublicacion === 1) {
                        multimediaHtml = `<video src="data:video/mp4;base64,${p.multimedia}" class="post-img" controls></video>`;
                        } else {
                            multimediaHtml = `<img src="data:image/*;base64,${p.multimedia}" class="post-img">`;
                        }

                        feed.innerHTML += `
                            <article class="post-card card">
                                <div class="post-header">
                                    <img src="data:image/*;base64,${p.fotoUsuario}" class="user-avatar">
                                    <div>
                                        <h3>${p.nombreUsuario} ${p.apellidoUsuario}</h3>
                                        <span>${p.nombreCategoria} • ${new Date(p.fechaCreacion).toLocaleDateString()}</span>
                                    </div>
                                </div>
                                <p class="post-desc">${p.descripcion}</p>
                                ${multimediaHtml} 
                                
                                <div class="post-footer">
                                    <button class="btn-like" data-id=${p.idPublicacion}>❤️ <span id="like-count-${p.idPublicacion}">${p.likes}</span> Likes</button>
                                    <a class="btn-comment" href="index.php?action=publicacion&id=${p.idPublicacion}">💬 ${p.comentarios} Comentarios</a>
                                </div>
                                
                            </article>
                        `;
                    });
                    
                    
                } else{
                    
                    alert("Error: " + data.mensaje);
                }
            });
        });
    });
</script>
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