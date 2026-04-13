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
        <a href="index.php" class="btn-cerrar">✕</a>

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

                    <div class="interaccion">
                        <button class="btn-corazon">❤</button>
                        <span class="contador-likes"><?php echo htmlspecialchars($publicacion['likes']) ?></span>
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

</script>