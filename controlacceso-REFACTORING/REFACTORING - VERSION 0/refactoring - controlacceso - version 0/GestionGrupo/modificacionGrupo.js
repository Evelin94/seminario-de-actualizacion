document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('searchForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const groupId = document.getElementById('idgrupo').value;

        fetch('obtener_grupo.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ groupId: groupId })
        })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                } else {
                    console.log(data);                   
                    document.getElementById('idgroup').value = data.idGrupos;    
                    idgroup.disabled = true;             
                    document.getElementById('grupo').value = data.Grupo;

                    document.getElementById('searchForm').style.display = 'none';
                    document.getElementById('updateForm').style.display = 'block';
                   
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al obtener los datos del grupo');
            });
    });

    document.getElementById('updateForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const groupId = document.getElementById('idgroup').value;
        const grupo = document.getElementById('grupo').value;
        
        fetch('actualizar_grupo.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ groupId: groupId, grupo: grupo })
        })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                } else {
                    alert('Grupo actualizado correctamente');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al actualizar los datos del grupo');
            });
    });
});

document.getElementById('backButton').addEventListener('click', function () {
    window.location.href = '../index.html';
});





