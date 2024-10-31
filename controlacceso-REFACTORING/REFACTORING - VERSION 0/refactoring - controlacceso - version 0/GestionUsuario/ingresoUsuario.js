document.getElementById('usuarioForm').addEventListener('submit', function (event) {
    event.preventDefault();
    const usuario = document.getElementById('usuario').value;
    const password = document.getElementById('password').value;

    const data = { usuario: usuario, password: password };

    fetch('insertar_usuario.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Usuario registrado exitosamente');
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
    window.location.href = '../index.html';
});
