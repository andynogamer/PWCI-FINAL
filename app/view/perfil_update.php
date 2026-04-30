<?php include __DIR__ . '/shared/header.php'; ?>

<div class="container">
    <h1>Actualizar datos</h1>
    
    <div id="errorAjax" style="color: red; margin-bottom: 15px; font-weight: bold; display: none;"></div>
    
    <form id="formRegistro" method="POST" action="index.php?action=api_update_usuario" enctype="multipart/form-data">
        
        

        

        <div class="input-group">
        <input type="text" name="nombre" placeholder="Nombre" required value="<?= htmlspecialchars($user['nombre']) ?>"> 
        </div>

        <div class="input-group">
        <input type="text" name="apellido" placeholder="Apellido" required value="<?= htmlspecialchars($user['apellido']) ?>">
        </div>

        <div class="input-group">
            <label>Fecha de Nacimiento</label>
            <input type="date" name="fechaNacimiento" required value="<?= htmlspecialchars($user['fechaNacimiento']) ?>">
        </div>
        
        <div class="input-group">
        <select name="genero" required>
            <option value="" disabled <?php if(!isset($user['genero'])): ?> selected <?php endif;?> >Género</option>
            <option value="M" <?php if(isset($user['genero']) && $user['genero'] === "M"): ?> selected <?php endif;?>>Masculino</option>
            <option value="F" <?php if(isset($user['genero']) && $user['genero'] === "F"): ?> selected <?php endif;?>>Femenino</option>
        </select>
        </div>

        <div class="input-group">  
        <input type="text" name="paisNacimiento" placeholder="País de nacimiento" required value="<?= htmlspecialchars($user['paisNacimiento'] ?? '') ?>">
        </div>
        
        <div class="input-group">
        <input type="text" name="nacionalidad" placeholder="Nacionalidad" required value="<?= htmlspecialchars($user['nacionalidad'] ?? '') ?>">
        </div>

        <button type="submit" id="btnSubmit">Actualizar</button>
    </form>
    
</div>

<script>



document.getElementById('formRegistro').addEventListener('submit', function(e) {
    e.preventDefault(); 

    const form = this;
    const formData = new FormData(form);
    const divError = document.getElementById('errorAjax');
    const btnSubmit = document.getElementById('btnSubmit');

    
    btnSubmit.disabled = true;
    btnSubmit.innerText = "Registrando...";
    divError.style.display = 'none'; 
    
    fetch(form.action, {
        method: 'POST',
        body: formData 
    })
    .then(response => response.json()) 
    .then(data => {
        btnSubmit.disabled = false;
        btnSubmit.innerText = "Registrarse";

        if (data.success) {
            
            window.location.href = 'index.php?action=perfil';
        } else {
            
            divError.innerHTML = data[1];
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