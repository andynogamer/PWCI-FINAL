<?php include __DIR__ . '/shared/header.php'; ?>

<div class="foro-banner" style="background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6));">
    <div class="foro-info">
        <img src="data:image/*;base64,<?php echo base64_encode($_SESSION['user']['foto']); ?>" class="foro-logo">
        <div>
            <h1><?php echo $_SESSION['user']['nombre']; ?>  <?php echo $_SESSION['user']['apellido']; ?></h1>
            <p><?php echo $_SESSION['user']['correoElectronico']; ?></p>
        </div>
    </div>
</div>

<div class="foro-container">

    <div class="filler-container">

    </div>

    <div id="feed-publicaciones" class="feed-section">
        <p class="loading">Cargando publicaciones...</p>
    </div>
</div>



<script>
document.addEventListener('DOMContentLoaded', () => {
    const idUsuario = <?php echo $_SESSION['user']['id']; ?>;
    
    
    function cargarFeed() {
        fetch(`index.php?action=api_get_publicaciones_usuario&idUsuario=${idUsuario}`)
            .then(res => res.json())
            .then(data => {
                const feed = document.getElementById('feed-publicaciones');
                feed.innerHTML = data.length ? '' : '<p class="empty">Aún no hay publicaciones en este mundial.</p>';
                
                data.forEach(p => {
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
                            <img src="data:image/*;base64,${p.multimedia}" class="post-img">
                            <div class="post-footer">
                                <button class="btn-like">❤️ ${p.likes} Likes</button>
                                <button class="btn-comment">💬 ${p.comentarios} Comentarios</button>
                            </div>
                        </article>
                    `;
                });
            });
    }

    cargarFeed();
});
</script>

<?php include __DIR__ . '/shared/footer.php'; ?>