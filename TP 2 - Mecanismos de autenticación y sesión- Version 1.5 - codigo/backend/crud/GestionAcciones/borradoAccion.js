document.getElementById('accionForm').addEventListener('submit', function (event) {
    event.preventDefault();
    const idaccion = document.getElementById('idaccion').value;

    const data = { idaccion: idaccion };

    fetch('borrado_accion.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Accion borrada exitosamente');
            } else {
                alert('Error al borrar accion: ' + data.message);
            }
        })
        .catch((error) => {
            console.error('Error:', error);
            alert('Error al borrar accion');
        });
});

document.addEventListener('DOMContentLoaded', function () {

    // Recupera los valores del usuario y la contraseña desde localStorage
    const sessionidusuario = localStorage.getItem('idusuario');
    const sessionusuario = localStorage.getItem('usuario');
    const sessionpassword = localStorage.getItem('contraseña');
    console.log(sessionidusuario, sessionusuario, sessionpassword);

    // Realiza la solicitud JSON al archivo PHP con método POST y headers personalizados
    fetch('../verificarPermiso.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Idusuario': sessionidusuario,
            'X-Usuario': sessionusuario,
            'X-Password': sessionpassword,
            'X-Operacion': '3' // BORRADO
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al obtener los datos');
            }
            return response.json();
        })
        .then(data => {
            console.log(data);
            // Verifica si hay un error en la respuesta
            if (data.error) {
                console.error('Error:', data.error);
                return;
            }

            if (data.success) {
                console.log('Borrado:', data.message);
                document.getElementById('usuarioForm').style.display = 'block';

            } else {
                console.log('Usuario no autorizado:', data.message);
                alert('No esta autorizado para ejecutar esta accion.')
            }

        })
        .catch(error => {
            console.error('Error al obtener los datos:', error);
        });

    document.getElementById('backButton').addEventListener('click', function () {
        window.location.href = '../dashboard.html';
    });
});

