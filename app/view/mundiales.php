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
    function cargarMundiales(){
        fetch('index.php?action=api_get_mundiales')
            
            .then(response => response.json())
            .then(data => {
                
                const container = document.getElementById('mundiales-container');
                container.innerHTML = ''; 

                if (data.error) {
                    container.innerHTML = `
                        <div class="error-message">
                            <h3>Ocurrió un error</h3>
                            <p>${data.mensaje}</p>
                        </div>
                    `;
                    return;
                }
                if (!Array.isArray(data)) {
                    container.innerHTML = `<p>Respuesta inesperada del servidor</p>`;
                    return;
                }

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
                                <div class="padding-container"></div>
                                <div class="options-mundial">
                                    <a href="index.php?action=foro&id=${m.id}" class="btn-card">Entrar al Foro</a>
                                    <?php if(isset($_SESSION['user']) && $_SESSION['user']['tipoUsuario'] == 2): ?>
                                    <div class="icon-mundial-container">
                                        <a href="index.php?action=admin_modificar_mundial&id=${m.id}" class="icon-mundial">
                                            <img src="resources/edit.png" alt="editar">
                                        </a>
                                        <button class="btn-delete-mundial" data-id="${m.id}">
                                            <img src="resources/delete.png" alt="eliminar">
                                        </button>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </article>
                    `;
                });
            })
            .catch(error => console.error('Error:', error));

    }

    cargarMundiales();
    document.getElementById('mundiales-container').addEventListener('click', (e) => {

        const deleteBtn = e.target.closest('.btn-delete-mundial');
        
        if (deleteBtn) {
            
            const id = deleteBtn.dataset.id;
            
            if (!confirm('¿Estás seguro de eliminar este mundial?')) return;
            console.log(id)
            fetch('index.php?action=api_delete_mundial', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    

                    cargarMundiales();
                } else{
                    
                    alert("Error: " + data.error);
                }
            })
            .catch(err => {
                
                console.error(err);
                alert("Error en la petición");
            });
        }

    });
});

</script>

<?php include __DIR__ . '/shared/footer.php'; ?>