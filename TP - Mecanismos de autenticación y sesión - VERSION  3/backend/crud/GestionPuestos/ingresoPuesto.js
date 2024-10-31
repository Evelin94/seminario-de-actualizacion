import { checkSessionStatus } from '../sessionTimer';

document.addEventListener('DOMContentLoaded', () => {

    // VERIFICAR QUE LA SESION NO HAYA EXPIRADO
    checkSessionStatus();

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

                document.getElementById('puestoForm').style.display = 'block';

            } else {
                console.log('Usuario no autorizado:', data.message);
                alert('No esta autorizado para ejecutar esta accion.')
            }

        })
        .catch(error => {
            console.error('Error al obtener los datos:', error);
        });





    // Fetch para obtener puestos
    fetch('getUsuarioPuesto.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Token': sessiontoken,
              }
        
    })

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
    fetch('getGrupoPuesto.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Token': sessiontoken,
        }
    })

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
    const sessiontoken = localStorage.getItem('token');
    const data = { usuario: parseInt(usuario), grupo: parseInt(grupo) };
    console.log(data);

    fetch('insertar_puesto.php', {
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
                alert('Usuario registrado exitosamente');
                // ALMACENAR NUEVO TOKEN
                console.log('New token : ', data.token);
                localStorage.setItem('token', data.token); 
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
