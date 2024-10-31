import { getAuthHeaders } from '../authHeaders';
import { checkSessionStatus } from '../sessionTimer';

const sessionidusuario = localStorage.getItem('idusuario');
const sessionusuario = localStorage.getItem('usuario');
const sessionpassword = localStorage.getItem('contraseña');
const sessiontoken = localStorage.getItem('token');
const sessionMetodo = localStorage.getItem('selectedAuthMethod');
console.log(sessionidusuario, sessionusuario, sessionpassword, sessiontoken);

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
            'X-Operacion': '4' // ACTUALIZACION
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
                console.log('Actualizacion:', data.message);
                document.getElementById('searchForm').style.display = 'block';

            } else {
                console.log('Usuario no autorizado:', data.message);
                alert('No esta autorizado para ejecutar esta accion.')
            }

        })
        .catch(error => {
            console.error('Error al obtener los datos:', error);
        });



    document.getElementById('searchForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const actionId = document.getElementById('idaccion').value;

        const headerSelect = getAuthHeaders('4'); // ACTUALIZACION
        console.log('headerSelect envio', headerSelect);

        fetch('obtener_accion.php', {
            method: 'POST',
            headers: headerSelect,
            body: JSON.stringify({ actionId: actionId })
        })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                } else {
                    console.log(data);                   
                    document.getElementById('idaction').value = data.data.idAcciones;    
                    idaction.disabled = true;    
                    document.getElementById('accion').value = data.data.Accion;

                    document.getElementById('searchForm').style.display = 'none';
                    document.getElementById('updateForm').style.display = 'block';

                    if (sessionMetodo === 'token') {
                        // ALMACENAR NUEVO TOKEN
                        console.log('New token : ', data.token);
                        localStorage.setItem('token', data.token);
                    }
                   
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al obtener los datos de la accion');
            });
    });

    document.getElementById('updateForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const actionId = document.getElementById('idaction').value;
        const accion = document.getElementById('accion').value;
        const sessiontoken = localStorage.getItem('token');

        const headerSelect = getAuthHeaders('4'); // ACTUALIZACION
        console.log('headerSelect envio', headerSelect);

        fetch('actualizar_accion.php', {
            method: 'POST',
            headers: headerSelect,
            body: JSON.stringify({ actionId: actionId, accion: accion })
        })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                } else {
                    alert('Accion actualizado correctamente');

                    if (sessionMetodo === 'token') {
                        // ALMACENAR NUEVO TOKEN
                        console.log('New token : ', data.token);
                        localStorage.setItem('token', data.token);
                    }     


                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al actualizar los datos de la accion');
            });
    });

    document.getElementById('backButton').addEventListener('click', function () {
        window.location.href = '../dashboard.html';
    });


});







