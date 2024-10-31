document.getElementById('permisoForm').addEventListener('submit', function (event) {
    event.preventDefault();
    const idpermiso = document.getElementById('idpermiso').value;   
    const data = { idpermiso: idpermiso };
       console.log(data);
    fetch('borrado_permiso.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Permiso borrado exitosamente');
            } else {
                alert('Error al borrar permiso: ' + data.message);
            }
        })
        .catch((error) => {
            console.error('Error:', error);
            alert('Error al borrar permiso');
        });
});

document.getElementById('backButton').addEventListener('click', function () {
    window.location.href = '../index.html';
});
