import { getAuthHeaders } from '../authHeaders';

// Recupera los valores del usuario y la contraseña desde localStorage
const sessionidusuario = localStorage.getItem('idusuario');
const sessionusuario = localStorage.getItem('usuario');
const sessionpassword = localStorage.getItem('contraseña');
const sessiontoken = localStorage.getItem('token');
const sessionMetodo = localStorage.getItem('selectedAuthMethod');

document.addEventListener('DOMContentLoaded', function () {
   
    console.log(sessionidusuario, sessionusuario, sessionpassword, sessiontoken);

    // Check user permissions
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
            if (data.error) {
                console.error('Error:', data.error);
                return;
            }

            if (data.success) {
                console.log('Actualizacion:', data.message);
                document.getElementById('searchForm').style.display = 'block';
            } else {
                console.log('Usuario no autorizado:', data.message);
                alert('No esta autorizado para ejecutar esta accion.');
            }
        })
        .catch(error => {
            console.error('Error al obtener los datos:', error);
        });

        
    // Handle search form submission
    document.getElementById('searchForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const userId = document.getElementById('idusuario').value;
    
        const headerSelect = getAuthHeaders('4'); // MODIFICACION
        console.log('headerSelect envio', headerSelect);

        fetch('obtener_usuario.php', {
            method: 'POST',
            headers:headerSelect,
            body: JSON.stringify({ userId: userId })
        })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                } else {
                    console.log(data);
                    const userData = data.data;
                    document.getElementById('id').value = userData.idUsuarios;
                    document.getElementById('id').disabled = true;
                    document.getElementById('usuario').value = userData.usuario;
                    document.getElementById('password').value = userData.password;
                    document.getElementById('searchForm').style.display = 'none';
                    document.getElementById('updateForm').style.display = 'block';

                    if (sessionMetodo === 'token') {
                    // Store new token
                    console.log('New token:', data.token);
                    localStorage.setItem('token', data.token);
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al obtener los datos del usuario');
            });
    });

    // Handle update form submission
    document.getElementById('updateForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const headerSelect = getAuthHeaders('4'); // MODIFICACION
        console.log('headerSelect envio', headerSelect);

        const userId = document.getElementById('id').value;
        const usuario = document.getElementById('usuario').value;
        const password = document.getElementById('password').value;
        const sessiontoken = localStorage.getItem('token');

        fetch('actualizar_usuario.php', {
            method: 'POST',
            headers: headerSelect,
            body: JSON.stringify({ userId: userId, name: usuario, password: password })
        })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                } else {
                    console.log(data);
                    alert('Usuario actualizado correctamente');
                    
                    if (sessionMetodo === 'token') {
                        // ALMACENAR NUEVO TOKEN
                        console.log('New token : ', data.token);
                        localStorage.setItem('token', data.token);
                    }

                 
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al actualizar los datos del usuario');
            });
    });

    // Handle back button click
    document.getElementById('backButton').addEventListener('click', function () {
        window.location.href = '../dashboard.html';
    });
});
