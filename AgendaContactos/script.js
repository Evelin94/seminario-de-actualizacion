document.addEventListener("DOMContentLoaded", function() {
    fetchContacts();

    const form = document.getElementById('contact-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        addContact();
    });
});

function fetchContacts() {
    fetch('contact.php')
        .then(response => response.json())
        .then(data => {
            const contactList = document.getElementById('contact-list');
            contactList.innerHTML = '';

            data.forEach(contact => {
                const contactDiv = document.createElement('div');
                contactDiv.innerHTML = `
                    <h3>${contact.nombre}</h3>
                    <p>Email: ${contact.email}</p>
                    <p>Dirección: ${contact.direccion}</p>
                    <p>Teléfonos: ${contact.telefonos}</p>
                    <button onclick="editContact(${contact.id})">Editar</button>
                    <button onclick="deleteContact(${contact.id})">Eliminar</button>
                `;
                contactList.appendChild(contactDiv);
            });
        });
}

function addContact() {
    const nombre = document.getElementById('nombre').value;
    const email = document.getElementById('email').value;
    const direccion = document.getElementById('direccion').value;
    const telefono = document.getElementById('telefono').value;

    fetch('contact.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ nombre, email, direccion, telefono })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Contacto agregado exitosamente");
            fetchContacts(); 
            document.getElementById('contact-form').reset(); 
        } else {
            alert("Error al agregar el contacto");
        }
    })
    .catch(error => console.error('Error:', error));

    function editContact(id) {
        const nombre = prompt("Nuevo nombre:");
        const email = prompt("Nuevo email:");
        const direccion = prompt("Nueva dirección:");
        const telefono = prompt("Nuevo teléfono:");
    
        fetch('contact.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id, nombre, email, direccion, telefono })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Contacto actualizado exitosamente");
                fetchContacts();
            } else {
                alert("Error al actualizar el contacto");
            }
        })
        .catch(error => console.error('Error:', error));
    }
    
    function deleteContact(id) {
        if (confirm("¿Estás seguro de que deseas eliminar este contacto?")) {
            fetch('contact.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Contacto eliminado exitosamente");
                    fetchContacts();
                } else {
                    alert("Error al eliminar el contacto");
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }
    
    
}
