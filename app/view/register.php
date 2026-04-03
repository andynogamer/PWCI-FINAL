<?php include __DIR__ . '/shared/header.php'; ?>

<div class="container">
    <h1>Crear Cuenta</h1>
    <form method="POST" action="index.php?action=register" enctype="multipart/form-data">
        
        <div class="profile-upload">
            <label for="foto">Foto de perfil (Opcional)</label>
            <input type="file" name="foto" id="foto" accept="image/*">
        </div>

        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="text" name="apellido" placeholder="Apellido" required>
        
        <div class="input-group">
            <label>Fecha de Nacimiento</label>
            <input type="date" name="fechaNacimiento" required>
        </div>

        <select name="genero">
            <option value="" disabled selected>Género</option>
            <option value="M">Masculino</option>
            <option value="F">Femenino</option>
        </select>

        <input type="text" name="paisNacimiento" placeholder="País de nacimiento" required>
        <input type="text" name="nacionalidad" placeholder="Nacionalidad" required>
        <input type="email" name="correo" placeholder="Correo electrónico" required>
        <input type="password" name="contrasena" placeholder="Contraseña" required>

        <button type="submit">Registrarse</button>
    </form>
    <a href="index.php?action=login">¿Ya tienes cuenta? Inicia sesión</a>
</div>

<?php include __DIR__ . '/shared/footer.php'; ?>