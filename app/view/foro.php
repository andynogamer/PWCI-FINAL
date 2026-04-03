<?php include __DIR__ . '/shared/header.php'; ?>

<div class="foro-banner" style="background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('data:image/*;base64,<?php echo base64_encode($mundial['banner']); ?>');">
    <div class="foro-info">
        <img src="data:image/*;base64,<?php echo base64_encode($mundial['logo']); ?>" class="foro-logo">
        <div>
            <h1><?php echo $mundial['nombre']; ?></h1>
            <p><?php echo $mundial['sede']; ?> • <?php echo date('Y', strtotime($mundial['fecha'])); ?></p>
        </div>
    </div>
</div>

<div class="foro-container">
    <?php if(isset($_SESSION['user'])): ?>
    <section class="create-post card">
        <form id="form-publicacion" enctype="multipart/form-data">
            <input type="hidden" name="idMundial" value="<?php echo $mundial['id']; ?>">
            <textarea name="descripcion" placeholder="¿Qué quieres compartir sobre este mundial?" required></textarea>
            
            <div class="post-actions">
                <div class="file-input">
                    <label for="multimedia">📷 Agregar Infografía</label>
                    <input type="file" name="multimedia" id="multimedia" accept="image/*" required>
                </div>
                <select name="idCategoria" id="select-categorias" required>
                    <option value="" disabled selected>Categoría</option>
                </select> 
                <button type="submit" class="btn-primary">Publicar</button>
            </div>
        </form>
    </section>
    <?php endif; ?>

    <div id="feed-publicaciones">
        <p class="loading">Cargando infografías...</p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const idMundial = <?php echo $mundial['id']; ?>;

    // 1. Cargar Categorías para el Select (API)
    fetch('index.php?action=api_get_categorias')
        .then(res => res.json())
        .then(data => {
            const select = document.getElementById('select-categorias');
            data.forEach(cat => {
                select.innerHTML += `<option value="${cat.id}">${cat.categoria}</option>`;
            });
        });

    // 2. Cargar Publicaciones (API)
    function cargarFeed() {
        fetch(`index.php?action=api_get_publicaciones&idMundial=${idMundial}`)
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