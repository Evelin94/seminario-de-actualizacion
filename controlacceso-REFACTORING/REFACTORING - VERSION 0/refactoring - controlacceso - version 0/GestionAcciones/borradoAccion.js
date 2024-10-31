document.getElementById('accionForm').addEventListener('submit', function (event) {
    event.preventDefault();
    const idaccion = document.getElementById('idaccion').value;

    const data = { idaccion: idaccion };

    fetch('borrado_accion.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Accion borrada exitosamente');
            } else {
                alert('Error al borrar accion: ' + data.message);
            }
        })
        .catch((error) => {
            console.error('Error:', error);
            alert('Error al borrar accion');
        });
});

document.getElementById('backButton').addEventListener('click', function () {
    window.location.href = '../index.html';
});
