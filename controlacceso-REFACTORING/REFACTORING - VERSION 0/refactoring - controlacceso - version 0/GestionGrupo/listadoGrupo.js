// Realiza la solicitud JSON al archivo PHP
fetch('listado_grupo.php')
    .then(response => response.json())
    .then(data => {
        // Crea la tabla
        const table = document.createElement('table');
        table.classList.add('table'); // Agrega clases de Bootstrap si las estás utilizando

        // Crea la cabecera de la tabla
        const thead = document.createElement('thead');
        const headerRow = document.createElement('tr');
        const titles = ['Id Grupo', 'Grupo']; // Títulos de las columnas
        titles.forEach(title => {
            const th = document.createElement('th');
            th.textContent = title;
            headerRow.appendChild(th);
        });
        thead.appendChild(headerRow);
        table.appendChild(thead);

        // Crea las filas de datos
        const tbody = document.createElement('tbody');
        data.forEach((item, index) => {
            const row = document.createElement('tr');
            Object.values(item).forEach(value => {
                const td = document.createElement('td');
                td.textContent = value;
                row.appendChild(td);
            });
            
            tbody.appendChild(row);
        });
        table.appendChild(tbody);

        // Inserta la tabla en el div con id 'listado'
        const listadoDiv = document.getElementById('listado');
        listadoDiv.innerHTML = ''; // Limpia el contenido previo
        listadoDiv.appendChild(table);
    })
    .catch(error => {
        console.error('Error al obtener los datos:', error);
    });
    
document.getElementById('backButton').addEventListener('click', function () {
    window.location.href = '../index.html';
});