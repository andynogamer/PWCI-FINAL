<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mundialist - Infografías</title>
    <link rel="stylesheet" href="css/stylesPost.css">
</head>
<body>
    <div class="visor-publicacion">
        <a href="#" onclick="cerrarDetalle(event)" class="btn-cerrar">✕</a>

        <div class="contenedor-principal">
            <div class="seccion-media">
                <?php if($publicacion['tipoPublicacion'] === 1): ?>
                <video src="data:video/mp4;base64,<?php echo $publicacion['multimedia']?>" controls></video>
                <?php else: ?>
                <img src="data:image/*;base64,<?php echo $publicacion['multimedia']; ?>" alt="Publicación">
                <?php endif; ?>
            </div>

            <aside class="sidebar-post">
                <header class="header-post">
                    <!-- FIXED: added missing closing quote after base64 data -->
                    <img src="data:image/*;base64,<?php echo $publicacion['fotoUsuario']; ?>" alt="Perfil" class="foto-perfil">
                    <div class="info-usuario">
                        <strong><?php echo htmlspecialchars($publicacion['nombreUsuario'] . " " . $publicacion['apellidoUsuario'] ?? "") ?></strong>
                        <span><?php echo htmlspecialchars($publicacion['fechaAprobacion']) ?></span>
                    </div>
                </header>

                <div class="contenido-scroll">
                    <p class="descripcion"><?php echo htmlspecialchars($publicacion['descripcion']) ?></p>

                    <div class="interaccion" id="interaccion-container">
                        
                            
                            <button class="btn-corazon" data-id="<?php echo $publicacion['idPublicacion']; ?>" style="background: none; border: none; cursor: pointer;">
                                ❤
                            </button>
                            
                            <span class="contador-likes" id="like-count">
                                <?= htmlspecialchars($publicacion['likes']) ?>
                            </span>
                        
                    </div>

                    <div class="lista-comentarios" id="lista-comentarios">
                        
                    </div>
                </div>

                <footer class="footer-comentarios">
                    <form  id="form-comentario">
                        <input type="hidden" name="idPublicacion" value="<?php echo $publicacion['idPublicacion'] ?>">
                        <input type="hidden" name="idComentarioPadre" value="">
                        <textarea name="comentario" placeholder="Escribe un comentario..." required></textarea>
                        <button type="submit" class="btn-publicar">Comentar</button>
                    </form>
                </footer>
            </aside>
        </div>
    </div>
</body>
</html>

<script>
    function cerrarDetalle(e) {
        e.preventDefault();

        if (window.history.length > 1) {
            history.back();
        } else {
            window.location.href = "index.php";
        }
    }

    
    document.addEventListener( 'DOMContentLoaded', ()=> {
        const idPublicacion = <?php echo $publicacion['idPublicacion']; ?>;
        
        const feedComment = document.getElementById('lista-comentarios');

        
        fetch('index.php?action=api_update_vistas', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: idPublicacion })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                console.log("vista agregada")
            } else{
                
                alert("Error: " + data.error);
            }
        })
        .catch(err => {
            
            console.error(err);
            alert("Error en la petición");
        });
        
        function cargarComentarios() {
            fetch(`index.php?action=api_get_comentarios&idPublicacion=${idPublicacion}`)
            .then(res => res.json())
            .then(data => {
                if (!data.length) {
                    feed.innerHTML = '<h2>¡Se el primero en comentar!</h2>';
                    return;
                }

                
                const htmlBuffer = data.map(c => {
                    
                    
                    
                    
                    return `
                        <div class="comentario">
                            <img src="data:image/jpeg;base64,${c.fotoUsuario}" class="foto-perfil" style="width: 35px; height: 35px;">
                            <div class="comentario-texto">
                                <strong>${c.nombreUsuario} ${c.apellidoUsuario}</strong>
                                <p>${c.texto}</p>
                            </div>
                            <?php if(isset($_SESSION['user']) && $_SESSION['user']['tipoUsuario'] == 2): ?>
                            <button class="btn-delete-comentario" data-id="${c.idComentario}">
                                <img src="resources/delete.png" alt="eliminar">
                            </button>
                            <?php endif; ?>
                        </div>
                        
                    `;
                }).join(''); // Unimos todo en un solo string

                // 2. Una sola actualización del DOM
                feedComment.innerHTML = htmlBuffer;
            });
        }
        cargarComentarios();
        
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
                    
                    const contador = document.getElementById(`like-count`);

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

        document.getElementById('interaccion-container').addEventListener('click', (e) => {

            if (e.target.classList.contains('btn-corazon')) {

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


        const formComentario = document.getElementById('form-comentario');
    
    if (formComentario) {
        formComentario.addEventListener('submit', async (e) => {
            e.preventDefault(); 
            
            
            const formData = new FormData(formComentario);
            
            
            const payload = Object.fromEntries(formData.entries());

            try {
                
                const btnSubmit = formComentario.querySelector('button[type="submit"]');
                btnSubmit.disabled = true;

                
                const response = await fetch('index.php?action=api_post_comentario', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json' 
                    },
                    body: JSON.stringify(payload) // Convertimos el objeto a JSON
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    
                    formComentario.reset(); 
                    cargarComentarios();
                    
                    
                } else {
                    console.error("Error del servidor:", data.mensaje);
                    alert("No se pudo publicar: " + (data.mensaje || "Error desconocido"));
                }

            } catch (error) {
                console.error('Error de conexión:', error);
                alert('Hubo un problema de conexión al intentar comentar.');
            } finally {
                
                const btnSubmit = formComentario.querySelector('button[type="submit"]');
                btnSubmit.disabled = false;
            }
        });
    }
    document.getElementById('lista-comentarios').addEventListener('click', (e) => {

        const deleteBtn = e.target.closest('.btn-delete-comentario');
        
        if (deleteBtn) {
            
            const id = deleteBtn.dataset.id;
            
            
            if (!confirm('¿Estás seguro de eliminar este comentario?')) return;
            
            fetch('index.php?action=api_delete_comentario', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    

                    cargarComentarios();
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