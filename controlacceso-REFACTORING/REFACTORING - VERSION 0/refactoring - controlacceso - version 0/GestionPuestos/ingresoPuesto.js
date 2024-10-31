document.addEventListener('DOMContentLoaded', () => {
    // Fetch para obtener usuarios
    fetch('getUsuarioPuesto.php')
        .then(response => response.json())
        .then(data => {
            console.log(data);
            if (data.error) {
                console.error('Error en el servidor:', data.error);
            } else {
                const usuarioSelect = document.getElementById('usuario');
                data.forEach(usuario => {
                    const option = document.createElement('option');
                    option.value = usuario.idUsuarios;
                    option.textContent = usuario.usuario;
                    usuarioSelect.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        });

    // Fetch para obtener grupos
    fetch('getGrupoPuesto.php')
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
});

document.getElementById('puestoForm').addEventListener('submit', function (event) {
    event.preventDefault();
    const usuario = document.getElementById('usuario').value;
    const grupo = document.getElementById('grupo').value;

    const data = { usuario: parseInt(usuario), grupo: parseInt(grupo) };
    console.log(data);

    fetch('insertar_puesto.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Usuario registrado exitosamente');
            } else {
                alert('Error al registrar usuario: ' + data.message);
            }
        })
        .catch((error) => {
            console.error('Error:', error);
            alert('Error al registrar usuario');
        });
});












document.getElementById('backButton').addEventListener('click', function () {
    window.location.href = '../index.html';
});
