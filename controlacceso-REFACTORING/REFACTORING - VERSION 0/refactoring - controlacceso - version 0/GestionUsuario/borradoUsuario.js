document.getElementById('usuarioForm').addEventListener('submit', function (event) {
    event.preventDefault();
    const idusuario = document.getElementById('idusuario').value;
   
    const data = { idusuario: idusuario };

    fetch('borrado_usuario.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Usuario borrado exitosamente');
            } else {
                alert('Error al borrar usuario: ' + data.message);
            }
        })
        .catch((error) => {
            console.error('Error:', error);
            alert('Error al borrar usuario');
        });
});

document.getElementById('backButton').addEventListener('click', function () {
    window.location.href = '../index.html';
});
