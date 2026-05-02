<?php include __DIR__ . '/../shared/header.php'; ?>

<div class="foro-container">

    <div class="filler-container">

    </div>

    <div id="feed-publicaciones" class="feed-section">
        <p class="loading">Cargando publicaciones...</p>
    </div>
</div>



<script>
document.addEventListener('DOMContentLoaded', () => {
    
    
    
    function cargarFeed() {
        fetch(`index.php?action=api_get_publicaciones_pendientes`)
            .then(res => {
                if (!res.ok) throw new Error("Error en la API");
                return res.json();
            })
            .then(data => {
                const feed = document.getElementById('feed-publicaciones');
                feed.innerHTML = data.length ? '' : '<p class="empty">Aún no hay publicaciones.</p>';
                
                data.forEach(p => {
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
                                <button class="btn-aprobar" data-id="${p.idPublicacion}"> Aprobar</button>
                                <button class="btn-eliminar" data-id="${p.idPublicacion}"> Eliminar</button>
                            </div>
                        </article>
                    `;
                });
            });
    }

    cargarFeed();

    document.getElementById('feed-publicaciones').addEventListener('click', (e) => {

    if (e.target.classList.contains('btn-aprobar')) {

        const id = e.target.dataset.id;

        fetch('index.php?action=api_update_publicacion_aprobada', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: id })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert("Publicación aprobada ");
                cargarFeed(); 
            } else {
                alert("Error: " + data.mensaje);
            }
        })
        .catch(err => {
            console.error(err);
            alert("Error en la petición");
        });
    }

    if (e.target.classList.contains('btn-eliminar')) {
        
        const id = e.target.dataset.id;

        if (!confirm('¿Estás seguro de eliminar esta publicación?')) return;

        fetch('index.php?action=api_delete_publicacion', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: id })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert("Publicación eliminada");
                cargarFeed(); 
            } else {
                alert("Error: " + data.mensaje);
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

<?php include __DIR__ . '/../shared/footer.php'; ?>