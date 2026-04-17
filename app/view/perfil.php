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

    const feed = document.getElementById('feed-publicaciones');
    
    
    function cargarFeed() {
        fetch(`index.php?action=api_get_publicaciones_usuario&idUsuario=${idUsuario}`)
        .then(res => res.json())
        .then(data => {
            if (!data.length) {
                feed.innerHTML = '<p class="empty">Aún no hay publicaciones en este mundial.</p>';
                return;
            }

            
            const htmlBuffer = data.map(p => {
                
                const fecha = new Date(p.fechaCreacion).toLocaleDateString();
                
                return `
                    <article class="post-card card">
                        <div class="post-header">
                            <img src="data:image/*;base64,${p.fotoUsuario}" class="user-avatar" alt="Avatar" loading="lazy">
                            <div>
                                <h3>${p.nombreUsuario} ${p.apellidoUsuario}</h3>
                                <span>${p.nombreCategoria} • ${fecha}</span>
                            </div>
                        </div>
                        <p class="post-desc">${p.descripcion}</p>
                        <img src="data:image/*;base64,${p.multimedia}" class="post-img" alt="Publicación" loading="lazy">
                        <div class="post-footer">
                            <button class="btn-like" data-id="${p.idPublicacion}">
                                ❤️ <span id="like-count-${p.idPublicacion}">${p.likes}</span> Likes
                            </button>
                            <a class="btn-comment" href="index.php?action=publicacion&id=${p.idPublicacion}">
                                💬 ${p.comentarios} Comentarios
                            </a>
                        </div>
                    </article>
                `;
            }).join(''); // Unimos todo en un solo string

            // 2. Una sola actualización del DOM
            feed.innerHTML = htmlBuffer;
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
                } else {
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

<?php include __DIR__ . '/shared/footer.php'; ?>