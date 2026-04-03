<?php include __DIR__ . '/shared/header.php'; ?>

<div class="foro-banner" style="background-image: url('data:image/jpeg;base64,<?php echo base64_encode($mundial['banner']); ?>');">
    <div class="foro-info">
        <img src="data:image/jpeg;base64,<?php echo base64_encode($mundial['logo']); ?>" class="foro-logo">
        <div class="foro-text">
            <h1><?php echo $mundial['nombre']; ?></h1>
            <p><?php echo $mundial['sede']; ?> • <?php echo date('Y', strtotime($mundial['fecha'])); ?></p>
        </div>
    </div>
</div>

<div class="foro-container">
    <section class="create-post card">
        <form id="form-publicacion">
            <textarea name="descripcion" placeholder="¿Qué quieres compartir sobre este mundial?" rows="3"></textarea>
            <div class="post-actions">
                <input type="file" name="multimedia" accept="image/*">
                <select name="idCategoria" id="select-categorias"></select>
                <button type="submit" class="admin-link" style="border:none; cursor:pointer;">Publicar</button>
            </div>
        </form>
    </section>

    <div id="feed-publicaciones">
        </div>
</div>

<?php include __DIR__ . '/shared/footer.php'; ?>