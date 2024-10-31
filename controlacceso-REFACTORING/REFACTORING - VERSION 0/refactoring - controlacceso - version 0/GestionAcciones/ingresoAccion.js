document.getElementById('accionForm').addEventListener('submit', function (event) {
    event.preventDefault();
    const accion = document.getElementById('accion').value;
  
    const data = { accion: accion };

    fetch('insertar_accion.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Accion registrada exitosamente');
            } else {
                alert('Error al registrar accion: ' + data.message);
            }
        })
        .catch((error) => {
            console.error('Error:', error);
            alert('Error al registrar accion');
        });
});

document.getElementById('backButton').addEventListener('click', function () {
    window.location.href = '../index.html';
});
