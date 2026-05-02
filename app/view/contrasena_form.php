<?php include __DIR__ . '/shared/header.php'; ?>

<div class="container">
    <h1>Cambiar contraseña</h1>

    <div id="errorAjax" style="color: red; margin-bottom: 15px; font-weight: bold; display: none;"></div>

    <form method="POST" id="formContrasena" action="index.php?action=update_contrasena">
        <input type="password" name="old_password" placeholder="Contraseña actual" required>
        <input type="password" name="new_password" placeholder="Nueva Contraseña" required>
        <input type="password" name="new_password_repeat" placeholder="Vuelva a ingresar su nueva contraseña" required>
        

        <button type="submit" id="btnSubmit">Cambiar</button>
    </form>

</div>

<script>
    document.getElementById('formContrasena').addEventListener('submit', function(e) {
        e.preventDefault(); 

        const form = this;
        const formData = new FormData(form);
        const divError = document.getElementById('errorAjax');
        const btnSubmit = document.getElementById('btnSubmit');

        
        btnSubmit.disabled = true;
        btnSubmit.innerText = "Cambiando...";
        divError.style.display = 'none'; 

        fetch(form.action, {
            method: 'POST',
            body: formData 
        })
        .then(response => response.json()) 
        .then(data => {
            btnSubmit.disabled = false;
            btnSubmit.innerText = "Cambiar";

            if (data.success) {
                
                window.location.href = 'index.php?action=perfil';
            } else {
                
                divError.innerHTML = data.error;
                divError.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error de red o servidor:', error);
            btnSubmit.disabled = false;
            btnSubmit.innerText = "Cambiar";
            divError.innerHTML = "Ocurrió un error inesperado al procesar la solicitud.";
            divError.style.display = 'block';
        });
    });
</script>

<?php include __DIR__ . '/shared/footer.php'; ?>