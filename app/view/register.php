<?php include __DIR__ . '/shared/header.php'; ?>

<div class="container">
    <h1>Crear Cuenta</h1>
    <?php if (isset($error) && $error): ?>
        <div style="color: red; margin-bottom: 10px;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>
    <form method="POST" action="index.php?action=register" enctype="multipart/form-data">
        
        <div class="profile-upload">
            <label for="foto">Foto de perfil (Opcional)</label>
            <input type="file" name="foto" id="foto" accept="image/*">
        </div>

        <input type="text" name="nombre" placeholder="Nombre" required value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>"> 
        <input type="text" name="apellido" placeholder="Apellido" required value="<?= htmlspecialchars($_POST['apellido'] ?? '') ?>">
        
        <div class="input-group">
            <label>Fecha de Nacimiento</label>
            <input type="date" name="fechaNacimiento" required value="<?= htmlspecialchars($_POST['fechaNacimiento'] ?? '') ?>">
        </div>

        <select name="genero">
            <option value="" disabled <?php if(!isset($_POST)): ?> selected <?php endif;?> >Género</option>
            <option value="M" <?php if(isset($_POST) && $_POST['genero'] === "M"): ?> selected <?php endif;?>>Masculino</option>
            <option value="F" <?php if(isset($_POST) && $_POST['genero'] === "F"): ?> selected <?php endif;?>>Femenino</option>
        </select>

        <input type="text" name="paisNacimiento" placeholder="País de nacimiento" required value="<?= htmlspecialchars($_POST['paisNacimiento'] ?? '') ?>">
        <input type="text" name="nacionalidad" placeholder="Nacionalidad" required value="<?= htmlspecialchars($_POST['nacionalidad'] ?? '') ?>">
        <input type="email" name="correo" placeholder="Correo electrónico" required value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>">
        <input type="password" name="contrasena" placeholder="Contraseña" required value="<?= htmlspecialchars($_POST['contrasena'] ?? '') ?>">

        <button type="submit">Registrarse</button>
    </form>
    <a href="index.php?action=login">¿Ya tienes cuenta? Inicia sesión</a>
</div>

<?php include __DIR__ . '/shared/footer.php'; ?>