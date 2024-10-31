document.getElementById('grupoForm').addEventListener('submit', function (event) {
    event.preventDefault();
    const grupo = document.getElementById('grupo').value;
  
    const data = { grupo: grupo };

    fetch('insertar_grupo.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Grupo registrado exitosamente');
            } else {
                alert('Error al registrar grupo: ' + data.message);
            }
        })
        .catch((error) => {
            console.error('Error:', error);
            alert('Error al registrar grupo');
        });
});

document.getElementById('backButton').addEventListener('click', function () {
    window.location.href = '../index.html';
});
