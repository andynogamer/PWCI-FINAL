<?php include __DIR__ . '/../shared/header.php'; ?>

<div class="container" style="max-width: 600px;">
    <h1>Gestionar Categorías</h1>
    
    <form method="POST" action="index.php?action=admin_categorias" style="margin-bottom: 2rem;">
        <input type="text" name="categoria" placeholder="Nombre de la categoría (ej. Jugadores, Estadios)" required>
        <button type="submit">Agregar Categoría</button>
    </form>

    <hr style="border-color: #334155; margin-bottom: 1.5rem;">

    <table style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr style="color: var(--primary); border-bottom: 2px solid #334155;">
                <th style="padding: 10px;">ID</th>
                <th style="padding: 10px;">Categoría</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categorias as $cat): ?>
                <tr style="border-bottom: 1px solid #334155;">
                    <td style="padding: 10px;"><?php echo $cat['id']; ?></td>
                    <td style="padding: 10px;"><?php echo $cat['categoria']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../shared/footer.php'; ?>