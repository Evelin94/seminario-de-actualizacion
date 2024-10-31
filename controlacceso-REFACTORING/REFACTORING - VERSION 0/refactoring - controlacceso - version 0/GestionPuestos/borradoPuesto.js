document.getElementById('puestoForm').addEventListener('submit', function (event) {
    event.preventDefault();
    const idpuesto = document.getElementById('idpuesto').value;   
    const data = { idpuesto: idpuesto };
    console.log(data);
    fetch('borrado_puesto.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Puesto borrado exitosamente');
            } else {
                alert('Error al borrar puesto: ' + data.message);
            }
        })
        .catch((error) => {
            console.error('Error:', error);
            alert('Error al borrar puesto');
        });
});

document.getElementById('backButton').addEventListener('click', function () {
    window.location.href = '../index.html';
});
