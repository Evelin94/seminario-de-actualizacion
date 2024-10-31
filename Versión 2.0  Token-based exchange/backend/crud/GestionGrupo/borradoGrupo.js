import { getAuthHeaders } from '../authHeaders';

const sessionidusuario = localStorage.getItem('idusuario');
const sessionusuario = localStorage.getItem('usuario');
const sessionpassword = localStorage.getItem('contraseña');
const sessiontoken = localStorage.getItem('token');
const sessionMetodo = localStorage.getItem('selectedAuthMethod');
console.log(sessionidusuario, sessionusuario, sessionpassword, sessiontoken);

document.addEventListener('DOMContentLoaded', function () {

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
                console.error('Error:', data.error);
                return;
            }

            if (data.success) {
                console.log('Borrado:', data.message);
                document.getElementById('grupoForm').style.display = 'block';

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


document.getElementById('grupoForm').addEventListener('submit', function (event) {
    event.preventDefault();
    const idgrupo = document.getElementById('idgrupo').value;
    const data = { idgrupo: idgrupo };
    const sessiontoken = localStorage.getItem('token');
    
    const headerSelect = getAuthHeaders('3'); // BORRADO
    console.log('headerSelect envio', headerSelect);

    fetch('borrado_grupo.php', {
        method: 'POST',
        headers: headerSelect,
        body: JSON.stringify(data)
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Grupo borrado exitosamente');

                if (sessionMetodo === 'token') {
                    // ALMACENAR NUEVO TOKEN
                    console.log('New token : ', data.token);
                    localStorage.setItem('token', data.token);
                }

            } else {
                alert('Error al borrar grupo: ' + data.message);
            }
        })
        .catch((error) => {
            console.error('Error:', error);
            alert('Error al borrar grupo');
        });
});

