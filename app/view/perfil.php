<?php include __DIR__ . '/shared/header.php'; ?>

<div class="foro-banner" style="background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6));">
    <div class="foro-info">

        <div class="avatar-wrapper">
            <?php if(empty($_SESSION['user']['foto'])): ?>
            <img src="resources/profile-default.png" class="foro-logo" alt="Foto de perfil">
            <?php else: ?>
            <img src="data:image/jpeg;base64,<?php echo base64_encode($_SESSION['user']['foto']); ?>" class="foro-logo" alt="Foto de perfil">
            <?php endif; ?>
            <button class="btn-change-avatar" id="btn-trigger-upload" title="Cambiar foto">+</button>
            <input type="file" id="input-update-foto" accept="image/jpeg, image/png" style="display: none;">
        </div>
        
        <div>
            <h1><?php echo $_SESSION['user']['nombre']; ?>  <?php echo $_SESSION['user']['apellido']; ?></h1>
            <p><?php echo $_SESSION['user']['correoElectronico']; ?></p>
        </div>
    </div>
    <div>
        <a href="index.php?action=modificar_perfil" class="btn-update-profile">Modificar perfil</a>
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
                let multimediaHtml = '';
                if (p.tipoPublicacion === 1) {
                    multimediaHtml = `<video src="data:video/mp4;base64,${p.multimedia}" class="post-img" controls></video>`;
                } else {
                    multimediaHtml = `<img src="data:image/*;base64,${p.multimedia}" class="post-img">`;
                }
                
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
                        ${multimediaHtml} 
                        <div class="post-footer">
                            <button class="btn-like" data-id="${p.idPublicacion}">
                                ❤️ <span id="like-count-${p.idPublicacion}">${p.likes}</span> Likes
                            </button>
                            <a class="btn-comment" href="index.php?action=publicacion&id=${p.idPublicacion}">
                                💬 ${p.comentarios} Comentarios
                            </a>
                            <div class="container-views">
                                <img src="resources/eye.png" alt="vistas">
                                <span>${p.vistas}</span> Vistas
                            </div>
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

    document.getElementById('btn-trigger-upload').addEventListener('click', () => {
        document.getElementById('input-update-foto').click();
    });

    document.getElementById('input-update-foto').addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;
        if (!file.type.startsWith('image/')) {
            alert('Solo se permiten imágenes');
            input.value = "";
            return;
        }

    
        const reader = new FileReader();
        reader.onload = e => document.getElementById('img-avatar-preview').src = e.target.result;
        reader.readAsDataURL(file);

        
        const formData = new FormData();
        formData.append('fotoPerfil', file); 

        
        fetch('index.php?action=api_update_avatar', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log("Foto actualizada en la base de datos");
                
            } else {
                alert("Error al actualizar la foto: " + data.error);
            }
        })
        .catch(error => console.error("Error de conexión:", error));
    });
});
</script>

<?php include __DIR__ . '/shared/footer.php'; ?>