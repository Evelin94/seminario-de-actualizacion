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
        const userId = document.getElementById('idusuario').value;

        fetch('obtener_usuario.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ userId: userId })
        })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                } else {
                    console.log(data);                   
                    document.getElementById('id').value = data.idUsuarios;    
                    id.disabled = true;             
                    document.getElementById('usuario').value = data.usuario;
                    document.getElementById('password').value = data.password;
                    document.getElementById('searchForm').style.display = 'none';
                    document.getElementById('updateForm').style.display = 'block';
                   
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al obtener los datos del usuario');
            });
    });

    document.getElementById('updateForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const userId = document.getElementById('id').value;
        const usuario = document.getElementById('usuario').value;
        const password = document.getElementById('password').value;
 
        fetch('actualizar_usuario.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ userId: userId, name: usuario, password: password })
        })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                } else {
                    alert('Usuario actualizado correctamente');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al actualizar los datos del usuario');
            });
    });
});

document.getElementById('backButton').addEventListener('click', function () {
    window.location.href = '../dashboard.html';
});




