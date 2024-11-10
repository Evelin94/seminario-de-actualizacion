document.addEventListener('DOMContentLoaded', () => {
    const userForm = document.getElementById('userForm');
    const groupForm = document.getElementById('groupForm');
    const actionForm = document.getElementById('actionForm');

    userForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const username = document.getElementById('nombre-usuario').value;
        const password = document.getElementById('contrasena').value;

        const userData = {
            operation: 'user',
            data: {
                username: username,
                password: password
            }
        };

        sendData(userData);
    });

    groupForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const groupName = document.getElementById('nombre-grupo').value;

        const groupData = {
            operation: 'group',
            data: {
                groupName: groupName
            }
        };

        sendData(groupData);
    });

    actionForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const actionName = document.getElementById('nombre-accion').value;

        const actionData = {
            operation: 'action',
            data: {
                actionName: actionName
            }
        };

        sendData(actionData);
    });

    function sendData(data) {
        console.log('Enviando datos:', data);
        fetch('create_user.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
        })
        .catch(error => {
            console.error('Error al enviar datos:', error);
        });
    }
});
