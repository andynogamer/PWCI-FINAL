<?php include __DIR__ . '/shared/header.php'; ?>

<section class="landing-hero">
    <h1>Explora la Historia de los Mundiales</h1>
    <p>Selecciona un foro para ver y compartir infografías.</p>
</section>

<div class="mundiales-grid">
    <?php foreach ($mundiales as $m): ?>
        <article class="mundial-card">
            <div class="card-banner">
                <img src="data:image/jpeg;base64,<?php echo base64_encode($m['banner']); ?>" alt="Banner <?php echo $m['nombre']; ?>">
            </div>
            <div class="card-content">
                <div class="card-header">
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($m['logo']); ?>" class="card-logo" alt="Logo">
                    <div class="card-title">
                        <h2><?php echo $m['nombre']; ?></h2>
                        <span><?php echo $m['sede']; ?> - <?php echo date('Y', strtotime($m['fecha'])); ?></span>
                    </div>
                </div>
                <p><?php echo substr($m['descripcion'], 0, 120); ?>...</p>
                <a href="index.php?action=foro&id=<?php echo $m['id']; ?>" class="btn-card">Entrar al Foro</a>
            </div>
        </article>
    <?php endforeach; ?>
</div>

<?php include __DIR__ . '/shared/footer.php'; ?>