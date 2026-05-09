<?php include __DIR__ . '/../shared/header.php'; ?>

<div class="container" style="max-width: 600px;">
    <h1>Gestionar Categorías</h1>
    
    <form method="POST" action="index.php?action=admin_categorias" style="margin-bottom: 2rem;">
        <?php if(is_array($resultado) && isset($resultado['error'])): ?>
            <div style="color: red; margin-bottom: 10px;">
            <?= htmlspecialchars($resultado['mensaje']) ?>
            </div>
        <?php endif; ?>
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
        <tbody id="categorias-container">
            <tr>
                <td colspan="2" style="text-align: center; color: var(--primary);">
                    Cargando categorias...
                </td>
            </tr>
        </tbody>
    </table>
</div>
<script src="js/jquery4.0.0.js"></script>
<script>
   document.addEventListener('DOMContentLoaded', () => {
        $.ajax({
            url: 'index.php?action=api_get_categorias',
            method: 'GET',
            dataType: 'json', 
            success: (data) => {
                const container = document.getElementById('categorias-container');
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

                
                data.forEach(c => {
                    const tr = document.createElement('tr');
                    tr.style.borderBottom = '1px solid #334155';

                    const tdId = document.createElement('td');
                    tdId.style.padding = '10px';
                    tdId.textContent = c.id; 

                    const tdCat = document.createElement('td');
                    tdCat.style.padding = '10px';
                    tdCat.textContent = c.categoria; 

                    tr.appendChild(tdId);
                    tr.appendChild(tdCat);
                    container.appendChild(tr);
                });
            },
            error: (xhr, status, error) => {
                
                console.error('Error en la petición AJAX: ', error);
            }
        });
    });
</script>

<?php include __DIR__ . '/../shared/footer.php'; ?>