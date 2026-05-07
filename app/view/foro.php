<?php include __DIR__ . '/shared/header.php'; ?>

<div class="foro-banner" style="background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('data:image/*;base64,<?php echo base64_encode($mundial['banner']); ?>');">
    <div class="foro-info">
        <img src="data:image/*;base64,<?php echo base64_encode($mundial['logo']); ?>" class="foro-logo">
        <div>
            <h1><?php echo $mundial['nombre']; ?></h1>
            <p><?php echo $mundial['sede']; ?> • <?php echo date('Y', strtotime($mundial['fecha'])); ?></p>
        </div>
    </div>
    <div class="foro-filter" id="foroFilter">
        
        <select id="ordenPublicaciones">
            <option value="" disabled selected>Filtrar publicaciones</option>
            <option value="cronologico" >Orden cronológico</option>
            <option value="pais">País</option>
            <option value="likes">Más likes</option>
            <option value="comentarios">Más comentarios</option>
        </select>

        <select id="paisesFilter" hidden>
            
        </select>
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

            <div class="container-img-post-preview">
                <img class="img-post-preview" src="resources/meme-example.jpg" alt="">
                <video class="video-post-preview" src="" controls></video>
            </div>

            <div class="fb-divider"></div>

            <div class="fb-post-actions">
                <input type="file" name="multimedia" accept="image/*,video/mp4" required hidden>
                <button class="action-btn">
                    <img src="resources/add-multimedia.png" alt="photo"> 
                </button>

                <select name="pais" id="select-pais" >
                    
                </select>

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
    const sedeSelect = document.getElementById('select-pais');
    const idMundial = <?php echo $mundial['id']; ?>;
    <?php if(isset($_SESSION['user'])): ?>

    // Dentro de tu DOMContentLoaded
    /*
    const inputMult = document.getElementById('input-multimedia');
    const imgPrev = document.getElementById('img-preview');
    const vidPrev = document.getElementById('video-preview');
    const prevCont = document.getElementById('preview-container');
    const MAX_FILE_SIZE = 16 * 1024 * 1024;

    inputMult.addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;

        if (file.size > MAX_FILE_SIZE) {
            alert("El archivo es demasiado pesado (Máximo 16MB).");
            this.value = ""; // Limpia el input
            if(prevCont) prevCont.style.display = 'none'; // Esconde la previa si existía
            return;
        }

        const reader = new FileReader();
        const isVideo = file.type.startsWith('video/');
        const isImage = file.type.startsWith('image/');

        reader.onload = function(e) {
            prevCont.style.display = 'block';
            if (isImage) {
                imgPrev.src = e.target.result;
                imgPrev.style.display = 'block';
                vidPrev.style.display = 'none';
            } else if (isVideo) {
                vidPrev.src = e.target.result;
                vidPrev.style.display = 'block';
                imgPrev.style.display = 'none';
            }
        };

        reader.readAsDataURL(file);
    });
        */
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

    function cargarPaisesFiltered(){

    }
    

    async function cargarPaises() {
        try {
            // Usamos el endpoint de 'all' filtrando solo los campos que necesitamos (nombre en español)
            const response = await fetch('https://restcountries.com/v3.1/all?fields=translations');
            const countries = await response.json();

            // 1. Extraemos los nombres comunes en español
            let nombresPaises = countries.map(c => c.translations.spa.common);

            // 2. Ordenamos alfabéticamente
            nombresPaises.sort((a, b) => a.localeCompare(b));

            // 3. Limpiamos el select y llenamos con los datos
            sedeSelect.innerHTML = '<option value="" disabled selected>Pais</option>';
            
            nombresPaises.forEach(pais => {
                const option = document.createElement('option');
                option.value = pais;
                option.textContent = pais;
                
                
                
                sedeSelect.appendChild(option);
            });

        } catch (error) {
            console.error("Error al cargar países:", error);
            sedeSelect.innerHTML = '<option value="" disabled>Error al cargar países</option>';
        }
    }

    function cargarFeed() {
        fetch(`index.php?action=api_get_publicaciones&idMundial=${idMundial}`)
            .then(res => res.json())
            .then(data => {
                const feed = document.getElementById('feed-publicaciones');
                feed.innerHTML = data.length ? '' : '<p class="empty">Aún no hay publicaciones en este mundial.</p>';
                
                // Modifica la parte del forEach en cargarFeed
                data.forEach(p => {
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
            });
    }
    <?php if(isset($_SESSION['user'])): ?>
    cargarPaises();
    <?php endif; ?>
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

    const selectOrden = document.getElementById('ordenPublicaciones');
    const paisFilter = document.getElementById('paisesFilter');
    paisFilter.addEventListener('change', function(){
        const paisSelected = this.value;
        fetch('index.php?action=api_get_publicaciones_by_pais', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: idMundial, pais: paisSelected })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const feed = document.getElementById('feed-publicaciones');
                
                feed.innerHTML = data.data.length ? '' : '<p class="empty">Aún no hay publicaciones en este mundial.</p>';
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
                
                alert("Error: " + data.error);
            }
        })
        .catch(err => {
            
            console.error(err);
            alert("Error en la petición");
        });
    })
    selectOrden.addEventListener('change', function(){
        
        paisFilter.hidden  = true;
        const orden = this.value;
        if (orden == "likes"){
            
            fetch('index.php?action=api_get_publicaciones_by_likes', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: idMundial })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const feed = document.getElementById('feed-publicaciones');
                    
                    feed.innerHTML = data.data.length ? '' : '<p class="empty">Aún no hay publicaciones en este mundial.</p>';
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
                    
                    alert("Error: " + data.error);
                }
            })
            .catch(err => {
                
                console.error(err);
                alert("Error en la petición");
            });
        }else if(orden == "cronologico"){
            cargarFeed();
        }else if(orden == "comentarios"){
            fetch('index.php?action=api_get_publicaciones_by_comentarios', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: idMundial })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const feed = document.getElementById('feed-publicaciones');
                    
                    feed.innerHTML = data.data.length ? '' : '<p class="empty">Aún no hay publicaciones en este mundial.</p>';
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
                    
                    alert("Error: " + data.error);
                }
            })
            .catch(err => {
                
                console.error(err);
                alert("Error en la petición");
            });
        }else if(orden == "pais"){
            
            paisFilter.hidden  = false;
            
            fetch('index.php?action=api_get_paisespublicacion_by_mundial', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: idMundial })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    paisFilter.innerHTML = '';
                    paisFilter.innerHTML = '<option value="" disabled selected>Selecciona</option>';
                    const paises = data.data;
                    paises.forEach(item => {
                        const option = document.createElement('option');

                        option.value = item.pais;
                        option.textContent = item.pais;

                        paisFilter.appendChild(option);
                    });
                    
                    
                    
                } else{
                    
                    alert("Error: " + data.error);
                }
            })
            .catch(err => {
                
                console.error(err);
                alert("Error en la petición");
            });
        }
    })

    document.querySelectorAll('.create-post').forEach(container => {
        const input = container.querySelector('input[type="file"]');
        const img = container.querySelector('.img-post-preview');
        const button = container.querySelector('.action-btn');
        const video = container.querySelector('.video-post-preview');
        const MAX_FILE_SIZE = 16 * 1024 * 1024;

        button.addEventListener('click', (e) => {
            
            e.preventDefault();
            
            input.click();
        });

        input.addEventListener('change', function () {
            const file = this.files[0];
            
            if (!file) return;

            if (file.size > MAX_FILE_SIZE) {
                alert("El archivo es demasiado pesado (Máximo 16MB).");
                this.value = ""; // Limpia el input
                
                return;
            }

            const reader = new FileReader();
            const isVideo = file.type.startsWith('video/');
            const isImage = file.type.startsWith('image/');

            reader.onload = function(e) {
                
                if (isImage) {
                    img.src = e.target.result;
                    img.style.display = 'block';
                    video.style.display = 'none';
                } else if (isVideo) {
                    video.src = e.target.result;
                    video.style.display = 'block';
                    img.style.display = 'none';
                }else{
                    alert('Solo se permiten imágenes y video');
                    input.value = "";
                    return;
                }
            };

            
            

            

            
            reader.readAsDataURL(file);
        });
    });

});
</script>