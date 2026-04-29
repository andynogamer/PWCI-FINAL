<?php include __DIR__ . '/shared/header.php'; ?>

<div class="container">
    <h1>Crear Cuenta</h1>
    
    <div id="errorAjax" style="color: red; margin-bottom: 15px; font-weight: bold; display: none;"></div>
    
    <form id="formRegistro" method="POST" action="index.php?action=register" enctype="multipart/form-data">
        
        <div class="profile-upload item-banner">
            <button type="button" class="add-image-btn">+ Agregar foto</button>
            <img class="img-banner" src="resources/profile-default.png"/>
            <input class="item-hide" type="file" name="foto" id="foto" accept="image/*">
        </div>

        <div class="separator"></div>

        <input type="text" name="nombre" placeholder="Nombre" required value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>"> 
        <input type="text" name="apellido" placeholder="Apellido" required value="<?= htmlspecialchars($_POST['apellido'] ?? '') ?>">
        
        <div class="input-group">
            <label>Fecha de Nacimiento</label>
            <input type="date" name="fechaNacimiento" required value="<?= htmlspecialchars($_POST['fechaNacimiento'] ?? '') ?>">
        </div>

        <select name="genero" required>
            <option value="" disabled <?php if(!isset($_POST['genero'])): ?> selected <?php endif;?> >Género</option>
            <option value="M" <?php if(isset($_POST['genero']) && $_POST['genero'] === "M"): ?> selected <?php endif;?>>Masculino</option>
            <option value="F" <?php if(isset($_POST['genero']) && $_POST['genero'] === "F"): ?> selected <?php endif;?>>Femenino</option>
        </select>

        <input type="text" name="paisNacimiento" placeholder="País de nacimiento" required value="<?= htmlspecialchars($_POST['paisNacimiento'] ?? '') ?>">
        <input type="text" name="nacionalidad" placeholder="Nacionalidad" required value="<?= htmlspecialchars($_POST['nacionalidad'] ?? '') ?>">
        <input type="email" name="correo" placeholder="Correo electrónico" required value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>">
        <input type="password" name="contrasena" placeholder="Contraseña" required value="<?= htmlspecialchars($_POST['contrasena'] ?? '') ?>">

        <button type="submit" id="btnSubmit">Registrarse</button>
    </form>
    <a href="index.php?action=login">¿Ya tienes cuenta? Inicia sesión</a>
</div>

<script>
// --- LÓGICA DE PREVISUALIZACIÓN DE IMAGEN ---
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

// --- LÓGICA AJAX ---
document.getElementById('formRegistro').addEventListener('submit', function(e) {
    e.preventDefault(); // Detenemos la recarga de página

    const form = this;
    const formData = new FormData(form);
    const divError = document.getElementById('errorAjax');
    const btnSubmit = document.getElementById('btnSubmit');

    // Estado de carga (evita múltiples clics)
    btnSubmit.disabled = true;
    btnSubmit.innerText = "Registrando...";
    divError.style.display = 'none'; 

    fetch(form.action, {
        method: 'POST',
        body: formData // Fetch procesa archivos y textos automáticamente
    })
    .then(response => response.json()) 
    .then(data => {
        btnSubmit.disabled = false;
        btnSubmit.innerText = "Registrarse";

        if (data.success) {
            // Éxito: Redirigir al login
            window.location.href = 'index.php?action=login';
        } else {
            // Error: Mostrar el mensaje de validación del modelo
            divError.innerHTML = data.error;
            divError.style.display = 'block';
        }
    })
    .catch(error => {
        console.error('Error de red o servidor:', error);
        btnSubmit.disabled = false;
        btnSubmit.innerText = "Registrarse";
        divError.innerHTML = "Ocurrió un error inesperado al procesar la solicitud.";
        divError.style.display = 'block';
    });
});
</script>

<?php include __DIR__ . '/shared/footer.php'; ?>