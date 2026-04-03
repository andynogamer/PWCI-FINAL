<?php include __DIR__ . '/shared/header.php'; ?>

<section class="landing-hero">
    <h1>Explora la Historia de los Mundiales</h1>
    <p>Selecciona un foro para ver y compartir infografías.</p>
</section>

<div id="mundiales-container" class="mundiales-grid">
    <p style="text-align: center; color: var(--primary);">Cargando mundiales...</p>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    fetch('index.php?action=api_get_mundiales')
        
        .then(response => response.json())
        .then(data => {
            
            const container = document.getElementById('mundiales-container');
            container.innerHTML = ''; 

            data.forEach(m => {
                console.log(m);
                container.innerHTML += `
                    <article class="mundial-card">
                        <div class="card-banner">
                            <img src="data:image/*;base64,${m.banner}" alt="Banner">
                        </div>
                        <div class="card-content">
                            <div class="card-header">
                                <img src="data:image/*;base64,${m.logo}" class="card-logo">
                                <div class="card-title">
                                    <h2>${m.nombre}</h2>
                                    <span>${m.sede} - ${m.fecha.split('-')[0]}</span>
                                </div>
                            </div>
                            <p>${m.descripcion.substring(0, 120)}...</p>
                            <a href="index.php?action=foro&id=${m.id}" class="btn-card">Entrar al Foro</a>
                        </div>
                    </article>
                `;
            });
        })
        .catch(error => console.error('Error:', error));
});
</script>

<?php include __DIR__ . '/shared/footer.php'; ?>