<?php include __DIR__ . '/../shared/header.php'; ?>

<div class="container mundial-form-container">
    <h1>Modificar Mundial </h1>
    <?php if($errormsg === null): ?>
    <form method="POST" action="index.php?action=admin_post_modificar_mundial" enctype="multipart/form-data">
        <div class="grid-input-container">
            <input  type="hidden" name="id" value="<?= htmlspecialchars($mundial['id'] ?? '') ?>">
            <input class="item-nombre" type="text" name="nombre" placeholder="Nombre del Mundial (ej. México 1986)" required 
            value="<?= htmlspecialchars($mundial['nombre'] ?? '') ?>">
            <input class="item-fecha" type="date" name="fecha" required title="Fecha de inicio"
            value="<?= htmlspecialchars($mundial['fecha'] ?? '') ?>">
            <input class="item-sede" type="text" name="sede" placeholder="Sede" required 
            value="<?= htmlspecialchars($mundial['sede'] ?? '') ?>">
            <div class="item-images">
                <div class="profile-upload item-logo">
                    
                    <button class="add-image-btn">+ Agregar logo</button>
                    <img class="img-logo" src="data:image/*;base64,<?php echo base64_encode($mundial['logo']); ?>"/>
                    <input class="item-hide" type="file" name="logo" accept="image/*" >
                    
                </div>

                <div class="profile-upload item-banner">
                    
                    <button class="add-image-btn">+ Agregar banner</button>
                    <img class="img-banner" src="data:image/*;base64,<?php echo base64_encode($mundial['banner']); ?>"/>
                    <input class="item-hide" type="file" name="banner" accept="image/*" >
                </div>
            </div>
            <textarea name="descripcion" placeholder="Descripción histórica..." class="item-descripcion"
             required style="width: 100%; background: var(--input-bg); color: white; border-radius: 10px; padding: 10px; margin-bottom: 1rem; border: none;"
             ><?= htmlspecialchars($mundial['descripcion'] ?? '') ?></textarea>
             
        </div>

        <button type="submit">Modificar Foro de Mundial</button>
    </form>
    <?php else: ?>
        <div style="color: red; margin-bottom: 10px;">
            <?= htmlspecialchars($errormsg) ?>
            </div>
    <?php endif; ?>
</div>

<script>
document.querySelectorAll('.profile-upload').forEach(container => {

    const input = container.querySelector('input[type="file"]');
    const img = container.querySelector('img');
    const button = container.querySelector('.add-image-btn');

    
    button.addEventListener('click', (e) => {
        e.preventDefault();
        input.click();
    });

    
    input.addEventListener('change', function () {
        const file = this.files[0];

        if (!file) return;

        
        if (!file.type.startsWith('image/')) {
            alert('Solo se permiten imágenes');
            input.value = "";
            return;
        }

        const reader = new FileReader();

        reader.onload = function (e) {
            img.src = e.target.result;
        };

        reader.readAsDataURL(file);
    });

});
</script>


<?php include __DIR__ . '/../shared/footer.php'; ?>

