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
        <section class="create-post fb-card">
        <form method="POST" id="form-publicacion" enctype="multipart/form-data" action="index.php?action=crear_publicacion">
            
            <input type="hidden" name="idMundial" value="<?php echo $mundial['id']; ?>">
            <input type="hidden" name="idUsuario" value="<?php echo $_SESSION['user']['id']; ?>">

            <div class="fb-post-top">
                <img src="data:image/*;base64,<?php echo base64_encode($_SESSION['user']['foto']); ?>" class="user-avatar">
                <textarea name="descripcion" placeholder="¿Qué estás pensando?" required></textarea>
            </div>

            <div class="fb-divider"></div>

            <div class="fb-post-actions">
                <label class="action-btn">
                    📷 Foto/Infografía
                    <input type="file" name="multimedia" accept="image/*" required hidden>
                </label>

                <select name="idCategoria" id="select-categorias" required>
                    <option value="" disabled selected>Categoría</option>
                </select>

                <button type="submit" class="btn-publish">Publicar</button>
            </div>
        </form>
    </section>
    <?php endif; ?>

    <div class="filler-container">

    </div>

    <div id="feed-publicaciones" class="feed-section">
        <p class="loading">Cargando infografías...</p>
    </div>
</div>

<?php include __DIR__ . '/shared/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const idMundial = <?php echo $mundial['id']; ?>;
    <?php if(isset($_SESSION['user'])): ?>

    const textarea = document.querySelector('textarea');
    textarea.addEventListener('input', () => {
        textarea.style.height = 'auto';
        textarea.style.height = textarea.scrollHeight + 'px';
    });

    
    fetch('index.php?action=api_get_categorias')
        .then(res => res.json())
        .then(data => {
            const select = document.getElementById('select-categorias');
            data.forEach(cat => {
                select.innerHTML += `<option value="${cat.id}">${cat.categoria}</option>`;
            });
        });

    <?php endif; ?>
    
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
                                <button class="btn-like"  data-id=${p.idPublicacion}>❤️ <span id="like-count-${p.idPublicacion}">${p.likes}</span> Likes</button>
                                <a class="btn-comment" href="index.php?action=publicacion&id=${p.idPublicacion}">💬 ${p.comentarios} Comentarios</a>
                            </div>
                        </article>
                    `;
                });
            });
    }

    cargarFeed();

    function cargarLikes(idPublicacion){
                
                fetch('index.php?action=api_get_likes', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: idPublicacion })

                })
                .then(res => res.json())
                .then(data => {
                    console.log(data);
                    if (data.success) {
                        
                        const contador = document.getElementById(`like-count-${idPublicacion}`);
                        
                        if(contador){
                            
                            contador.textContent = data.response;
                        }
                        
                        
                    } else {
                        alert("Error: " + data.mensaje);
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert("Error en la petición");
                });
    }

    document.getElementById('feed-publicaciones').addEventListener('click', (e) => {
        if (e.target.classList.contains('btn-like')) {

            const id = e.target.dataset.id;

            fetch('index.php?action=api_post_like', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    

                    cargarLikes(id); 
                } else{
                    
                    alert("Error: " + data.error);
                }
            })
            .catch(err => {
                
                console.error(err);
                alert("Error en la petición");
            });
        }

    });
});
</script>