document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('searchForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const groupId = document.getElementById('idgrupo').value;

        fetch('obtener_grupo.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ groupId: groupId })
        })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                } else {
                    console.log(data);                   
                    document.getElementById('idgroup').value = data.idGrupos;    
                    idgroup.disabled = true;             
                    document.getElementById('grupo').value = data.Grupo;

                    document.getElementById('searchForm').style.display = 'none';
                    document.getElementById('updateForm').style.display = 'block';
                   
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al obtener los datos del grupo');
            });
    });

    document.getElementById('updateForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const groupId = document.getElementById('idgroup').value;
        const grupo = document.getElementById('grupo').value;
        
        fetch('actualizar_grupo.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ groupId: groupId, grupo: grupo })
        })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                } else {
                    alert('Grupo actualizado correctamente');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al actualizar los datos del grupo');
            });
    });

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

});

document.getElementById('backButton').addEventListener('click', function () {
    window.location.href = '../dashboard.html';
});



