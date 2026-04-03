<?php include __DIR__ . '/shared/header.php'; ?>

<div class="container">
    <h1>Iniciar Sesión</h1>

    <form method="POST" action="index.php?action=login">
        <input type="email" name="correo" placeholder="Correo" required>
        <input type="password" name="contrasena" placeholder="Contraseña" required>

        <button type="submit">Entrar</button>
    </form>

    <a href="index.php?action=register">Crear cuenta</a>
</div>

<?php include __DIR__ . '/shared/footer.php'; ?>