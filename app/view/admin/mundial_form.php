<?php include __DIR__ . '/../shared/header.php'; ?>

<div class="container">
    <h1>Nuevo Mundial (Admin)</h1>
    <form method="POST" action="index.php?action=admin_mundiales" enctype="multipart/form-data">
        <input type="text" name="nombre" placeholder="Nombre del Mundial (ej. México 1986)" required>
        <input type="date" name="fecha" required title="Fecha de inicio">
        <input type="text" name="sede" placeholder="Sede" required>
        
        <div class="profile-upload">
            <label>Logo del Mundial</label>
            <input type="file" name="logo" accept="image/*" required>
        </div>

        <div class="profile-upload">
            <label>Banner del Foro</label>
            <input type="file" name="banner" accept="image/*" required>
        </div>

        <textarea name="descripcion" placeholder="Descripción histórica..." required style="width: 100%; background: var(--input-bg); color: white; border-radius: 10px; padding: 10px; margin-bottom: 1rem; border: none;"></textarea>

        <button type="submit">Crear Foro de Mundial</button>
    </form>
</div>

<?php include __DIR__ . '/../shared/footer.php'; ?>