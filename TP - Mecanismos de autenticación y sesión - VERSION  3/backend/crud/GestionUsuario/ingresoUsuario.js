import { getAuthHeaders } from '../authHeaders';
import { checkSessionStatus } from '../sessionTimer';
// Recupera los valores del usuario y la contraseña desde localStorage
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
            'X-Operacion': '2' // CREACION
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
                console.log('Creacion:', data.message);

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


document.getElementById('usuarioForm').addEventListener('submit', function (event) {
    event.preventDefault();
   
    const headerSelect = getAuthHeaders('2'); // CREACION
    console.log('headerSelect envio', headerSelect);


    const usuario = document.getElementById('usuario').value;
    const password = document.getElementById('password').value;

    const data = { usuario: usuario, password: password };
 
    fetch('insertar_usuario.php', {
        method: 'POST',
        headers: headerSelect,   
        body: JSON.stringify(data)
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Usuario registrado exitosamente');

                if (sessionMetodo === 'token') {
                    // ALMACENAR NUEVO TOKEN
                    console.log('New token : ', data.token);
                    localStorage.setItem('token', data.token);
                }



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
    window.location.href = '../dashboard.html';
});

