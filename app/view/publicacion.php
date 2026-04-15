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
                <img src="data:image/*;base64,<?php echo $publicacion['multimedia']; ?>" alt="Publicación">
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

                    <div class="lista-comentarios">
                        <?php /*var_dump($comentarios); exit;*/if ($comentarios): ?>
                            
                            <?php foreach ($comentarios as $c): ?>
                                <div class="comentario">
                                    <img src="data:image/jpeg;base64, <?= $c['fotoUsuario'] ?>" class="foto-perfil">
                                    <strong><?= htmlspecialchars($c['nombreUsuario'] . " " . $c['apellidoUsuario'] ?? '') ?></strong>
                                    <p><?= htmlspecialchars($c['texto']) ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <h2>¡Se el primero en comentar!</h2>
                        <?php endif; ?>
                        
                        
                    </div>
                </div>

                <footer class="footer-comentarios">
                    <form  method="POST" action="index.php?action=crear_comentario">
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

    });
</script>