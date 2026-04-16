<?php include __DIR__ . '/../shared/header.php'; ?>

<div class="container mundial-form-container">
    <h1>Nuevo Mundial </h1>
    <form method="POST" action="index.php?action=admin_mundiales" enctype="multipart/form-data">
        <div class="grid-input-container">
            <input class="item-nombre" type="text" name="nombre" placeholder="Nombre del Mundial (ej. México 1986)" required>
            <input class="item-fecha" type="date" name="fecha" required title="Fecha de inicio">
            <input class="item-sede" type="text" name="sede" placeholder="Sede" required>
            <div class="item-images">
                <div class="profile-upload item-logo">
                    
                    <button class="add-image-btn">+ Agregar logo</button>
                    <img class="img-logo" src="resources/profile-default.png"/>
                    <input class="item-hide" type="file" name="logo" accept="image/*" required>
                    
                </div>

                <div class="profile-upload item-banner">
                    
                    <button class="add-image-btn">+ Agregar banner</button>
                    <img class="img-banner" src="resources/banner-default.png"/>
                    <input class="item-hide" type="file" name="banner" accept="image/*" required>
                </div>
            </div>
            <textarea name="descripcion" placeholder="Descripción histórica..." class="item-descripcion"
             required style="width: 100%; background: var(--input-bg); color: white; border-radius: 10px; padding: 10px; margin-bottom: 1rem; border: none;"></textarea>
        </div>
        <button type="submit">Crear Foro de Mundial</button>
    </form>
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

