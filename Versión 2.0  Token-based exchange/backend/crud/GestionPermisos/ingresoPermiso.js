document.addEventListener('DOMContentLoaded', () => {
    
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
                document.getElementById('permisoForm').style.display = 'block';
                datosSelect();

            } else {
                console.log('Usuario no autorizado:', data.message);
                alert('No esta autorizado para ejecutar esta accion.')
            }

        })
        .catch(error => {
            console.error('Error al obtener los datos:', error);
        });

    function datosSelect() {
      
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
    }

   

    document.getElementById('backButton').addEventListener('click', function () {
        window.location.href = '../dashboard.html';
    });

});

document.getElementById('permisoForm').addEventListener('submit', function (event) {
    event.preventDefault();
    const grupo = document.getElementById('grupo').value;
    const accion = document.getElementById('accion').value;

    const data = { grupo: parseInt(grupo), accion: parseInt(accion) };
    const sessiontoken = localStorage.getItem('token');
    console.log("Datos a ingresar:")
    console.log(data);
    console.log(sessiontoken);
    fetch('insertar_permiso.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Token': sessiontoken
        },
        body: JSON.stringify(data)
    })
        .then(response => response.json())
        .then(data => {
            console.log('Response from server:', data);  // Añade esta línea para más información

            if (data.success) {
                alert('Permiso registrado exitosamente');
                // Almacenar nuevo token
                console.log('New token : ', data.token);
                localStorage.setItem('token', data.token);
            } else {
                alert('Error al registrar permiso: ' + (data.message || 'Respuesta no válida del servidor'));
            }
        })
        .catch((error) => {
            console.error('Error:', error);
            alert('Error al registrar permiso');
        });

});



