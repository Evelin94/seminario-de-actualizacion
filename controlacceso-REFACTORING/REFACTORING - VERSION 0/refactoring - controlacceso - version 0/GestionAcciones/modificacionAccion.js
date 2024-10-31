document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('searchForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const actionId = document.getElementById('idaccion').value;

        fetch('obtener_accion.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ actionId: actionId })
        })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                } else {
                    console.log(data);                   
                    document.getElementById('idaction').value = data.idAcciones;    
                    idaction.disabled = true;    
                    document.getElementById('accion').value = data.Accion;

                    document.getElementById('searchForm').style.display = 'none';
                    document.getElementById('updateForm').style.display = 'block';
                   
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al obtener los datos de la accion');
            });
    });

    document.getElementById('updateForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const actionId = document.getElementById('idaction').value;
        const accion = document.getElementById('accion').value;
        
        fetch('actualizar_accion.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ actionId: actionId, accion: accion })
        })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                } else {
                    alert('Accion actualizado correctamente');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al actualizar los datos de la accion');
            });
    });
});

document.getElementById('backButton').addEventListener('click', function () {
    window.location.href = '../index.html';
});





