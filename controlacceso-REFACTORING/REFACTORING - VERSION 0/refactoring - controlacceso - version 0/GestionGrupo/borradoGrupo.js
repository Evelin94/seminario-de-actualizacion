document.getElementById('grupoForm').addEventListener('submit', function (event) {
    event.preventDefault();
    const idgrupo = document.getElementById('idgrupo').value;

    const data = { idgrupo: idgrupo };

    fetch('borrado_grupo.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Grupo borrado exitosamente');
            } else {
                alert('Error al borrar grupo: ' + data.message);
            }
        })
        .catch((error) => {
            console.error('Error:', error);
            alert('Error al borrar grupo');
        });
});

document.getElementById('backButton').addEventListener('click', function () {
    window.location.href = '../index.html';
});
