document.addEventListener('DOMContentLoaded', () => {

    // Fetch para obtener grupos
    fetch('getGrupoPermiso.php')
        .then(response => response.json())
        .then(data => {
            console.log(data);
            if (data.error) {
                console.error('Error en el servidor:', data.error);
            } else {
                const grupoSelect = document.getElementById('grupo');
                data.forEach(grupo => {
                    const option = document.createElement('option');
                    option.value = grupo.idGrupos;
                    option.textContent = grupo.Grupo;
                    grupoSelect.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        });
        

    // Fetch para obtener acciones
    fetch('getAccionPermiso.php')
        .then(response => response.json())
        .then(data => {
            console.log(data);
            if (data.error) {
                console.error('Error en el servidor:', data.error);
            } else {
                const accionSelect = document.getElementById('accion');
                data.forEach(accion => {
                    const option = document.createElement('option');
                    option.value = accion.idAcciones;
                    option.textContent = accion.Accion;
                    accionSelect.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        });


});

document.getElementById('permisoForm').addEventListener('submit', function (event) {
    event.preventDefault();
    const grupo = document.getElementById('grupo').value;
    const accion = document.getElementById('accion').value;

    const data = { grupo: parseInt(grupo), accion: parseInt(accion) };
    console.log(data);

    fetch('insertar_permiso.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Permiso registrado exitosamente');
            } else {
                alert('Error al registrar permiso: ' + data.message);
            }
        })
        .catch((error) => {
            console.error('Error:', error);
            alert('Error al registrar permiso');
        });
});

document.getElementById('backButton').addEventListener('click', function () {
    window.location.href = '../index.html';
});


