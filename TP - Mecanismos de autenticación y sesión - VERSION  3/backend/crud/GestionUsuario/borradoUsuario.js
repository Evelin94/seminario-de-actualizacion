import { getAuthHeaders } from '../authHeaders';
import { checkSessionStatus } from '../sessionTimer';
// Recupera los valores del usuario y la contraseña desde localStorage
const sessionidusuario = localStorage.getItem('idusuario');
const sessionusuario = localStorage.getItem('usuario');
const sessionpassword = localStorage.getItem('contraseña');
const sessiontoken = localStorage.getItem('token');
const sessionMetodo = localStorage.getItem('selectedAuthMethod');

document.addEventListener('DOMContentLoaded', function () {


    // VERIFICAR QUE LA SESION NO HAYA EXPIRADO
    checkSessionStatus();
 
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
                document.getElementById('usuarioForm').style.display = 'block';
            } else {
                console.log('Usuario no autorizado:', data.message);
                alert('No está autorizado para ejecutar esta acción.');
            }
        })
        .catch(error => {
            console.error('Error al obtener los datos:', error);
        });

    document.getElementById('backButton').addEventListener('click', function () {
        window.location.href = '../dashboard.html';
    });


    
    document.getElementById('usuarioForm').addEventListener('submit', function (event) {
        event.preventDefault();
        const idusuario = document.getElementById('idusuario').value;
        const headerSelect = getAuthHeaders('3'); // BORRADO
        const data = { idusuario: idusuario };

        fetch('borrado_usuario.php', {
            method: 'POST',
            headers:headerSelect,
            body: JSON.stringify(data)
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Usuario borrado exitosamente');

                    if (sessionMetodo === 'token') {
                        // ALMACENAR NUEVO TOKEN
                        console.log('New token : ', data.token);
                        localStorage.setItem('token', data.token);
                    }


                } else {
                    alert('Error al borrar usuario: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al borrar usuario');
            });
    });
});

