document.addEventListener('DOMContentLoaded', function () {
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
    window.location.href = '../index.html';
});





