document.addEventListener('DOMContentLoaded', function () {

    // Recupera los valores del usuario y la contraseña desde localStorage
    const sessionidusuario = localStorage.getItem('idusuario');
    const sessionusuario = localStorage.getItem('usuario');
    const sessionpassword = localStorage.getItem('contraseña');
    const sessiontoken = localStorage.getItem('token');
    console.log(sessionidusuario, sessionusuario, sessionpassword, sessiontoken);

    // Realiza la solicitud JSON al archivo PHP con método POST y headers personalizados
    fetch('../verificarPermiso.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Idusuario': sessionidusuario,
            'X-Usuario': sessionusuario,
            'X-Token': sessiontoken,
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
                console.error('Error');
                return;
            }

            if (data.success) {
                console.log('Borrado:', data.message);
                document.getElementById('permisoForm').style.display = 'block';

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



document.getElementById('permisoForm').addEventListener('submit', function (event) {
    event.preventDefault();
    const idpermiso = document.getElementById('idpermiso').value;   
    const sessiontoken = localStorage.getItem('token');
    const data = { idpermiso: idpermiso };
       console.log(data);
    fetch('borrado_permiso.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Token': sessiontoken
        },
        body: JSON.stringify(data)
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Permiso borrado exitosamente');
                // Almacenar nuevo token
                console.log('New token:', data.token);
                localStorage.setItem('token', data.token);


            } else {
                alert('Error al borrar permiso: ' + data.message);
            }
        })
        .catch((error) => {
            console.error('Error:', error);
            alert('Error al borrar permiso');
        });
});

