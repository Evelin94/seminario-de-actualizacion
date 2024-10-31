function create() 
{
    // ALMACENAR FORMULARIO
    formulario = new FormData(createform);
    
    // CONVERTIR FormData A UN OBJETO JSON
    var formDataJSON = {};
    formulario.forEach(function (value, key) {
        formDataJSON[key] = value;
    });

    // CONVERTIR OBJETO JSON EN CADENA JSON
    var jsonData = JSON.stringify(formDataJSON);
  
    console.log('Json');
    console.log(jsonData);

    // ENVIAR PETICION JSON CREATE
    fetch('create.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'},
        body: jsonData
    })
        .then(response => response.json())
        .then(data => {

            // MENSAJE DE RESULTADO DE LA PETICION 
            const mensajeDiv = document.getElementById('mensaje');

            if (data.success) {
                 mensajeDiv.textContent = data.message;
                 mensajeDiv.style.color = 'green';
            } else {
                 mensajeDiv.textContent = data.message;
                 mensajeDiv.style.color = 'red';
            }

            // BORRAR MENSAJE Y LIMPIAR FORMULARIO DESPUES DE 5 SEGUNDOS
            setTimeout(() => {
                // Borrar el mensaje del div
                mensajeDiv.textContent = '';
                // Resetear el formulario
                document.getElementById('createform').reset();
            }, 5000); // 5000 milisegundos = 5 segundos
        })
        .catch(error => {
            // Manejo de errores de red u otros errores
            console.error('Error al realizar la petición:', error);
        });

}    

function editar() {

    console.log('Editar');

    // ALMACENAR FORMULARIO
    formulario = new FormData(editform);

    // CONVERTIR FormData A UN OBJETO JSON
    var formDataJSON = {};
    formulario.forEach(function (value, key) {
        formDataJSON[key] = value;
    });

    // CONVERTIR OBJETO JSON EN CADENA JSON
    var jsonData = JSON.stringify(formDataJSON);

    console.log('Controller.js');
    console.log(jsonData);

    // ENVIAR PETICION JSON EDIT
    fetch('edit.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: jsonData
    })          
        
        .then(response => response.json())
        .then(data => {
        console.log(data);
            // MENSAJE DE RESULTADO DE LA PETICION 
            const mensajeDiv = document.getElementById('mensaje');

            if (data.success) {
                mensajeDiv.textContent = data.message;
                mensajeDiv.style.color = 'green';
            } else {
                mensajeDiv.textContent = data.message;
                mensajeDiv.style.color = 'red';
            }

            // BORRAR MENSAJE 
            setTimeout(() => {
                // Borrar el mensaje del div
                mensajeDiv.textContent = '';
               
            }, 5000); // 5000 milisegundos = 5 segundos

        })    

}

function eliminar(id) {

    console.log('Eliminar');
    console.log(id); 

    const data = {
        numero: id
    };
    
    fetch('delete.php', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })

        .then(response => response.json())
        .then(data => {
            console.log(data);
            // MENSAJE DE RESULTADO DE LA PETICION 
            const mensajeDiv = document.getElementById('mensaje');

            if (data.success) {
                mensajeDiv.textContent = data.message;
                mensajeDiv.style.color = 'green';
            } else {
                mensajeDiv.textContent = data.message;
                mensajeDiv.style.color = 'red';
            }

            // Obtener el botón por su ID
            var boton = document.getElementById("botondelete");

            // Deshabilitar el botón
            boton.style.display = 'none';

            // Obtener el elemento del cuerpo de la tabla por su ID
            var TableDelete = document.getElementById('tabladelete');

            // Ocultar el cuerpo de la tabla cambiando su estilo de visualización a 'none'
            TableDelete.style.display = 'none';

            // BORRAR MENSAJE 
            setTimeout(() => {
                // Borrar el mensaje del div
                mensajeDiv.textContent = '';
            }, 5000); // 5000 milisegundos = 5 segundos

        })    




}


// CARGA DE CONTACTOS
function cargacontactos() {
 
    fetch('tablacontactos.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
    })

        .then(response => response.json())
        .then(data => {

            console.log(data);
            // BORRAR BODY DE TABLA
            tbody = document.getElementById('bodytable');
            while (tbody.firstChild) {
                tbody.removeChild(tbody.firstChild);
            }

            data.forEach(contactos => {

                console.log(contactos.idContactos);

                // CREAR ELEMENTOS ROW Y TABLEDATA
                row = document.createElement('tr');
                nombre = document.createElement('td');
                apellido = document.createElement('td');
                email = document.createElement('td');
                telefono1 = document.createElement('td');
                telefono2 = document.createElement('td');
                telefono3 = document.createElement('td');
                acciones = document.createElement('td');
                nombre.innerHTML = contactos.Nombre;
                apellido.innerHTML = contactos.Apellido;
                email.innerHTML = contactos.Email;
                telefono1.innerHTML = contactos.NumeroTelefono[0];
                telefono2.innerHTML = typeof contactos.NumeroTelefono[1] !== 'undefined' ? contactos.NumeroTelefono[1] : "";
                telefono3.innerHTML = typeof contactos.NumeroTelefono[2] !== 'undefined' ? contactos.NumeroTelefono[2] : "";
                
                // PREPARAR ARRAY PARA PASAR PARAMETROS
                parametros = [];
                parametros[0] = contactos.idContactos;
                parametros[1] = contactos.Nombre;
                parametros[2] = contactos.Apellido;
                parametros[3] = contactos.Email;
                parametros[4] = contactos.NumeroTelefono;
                var parametrosString = parametros.join(',');

                //CREACION DE BOTONES
                var boton = document.createElement('button');
                var boton1 = document.createElement('button');
                boton.innerHTML = '<img src="imagenes/editar.png" height="32" width="32" alt="Nuevo" > Editar';
                boton1.innerHTML = '<img src="imagenes/borrar.png" height="32" width="32" alt="Nuevo"> Borrar';
                boton.classList.add('botoncontacto');
                boton1.classList.add('botoncontacto');
                boton.onclick = function () { window.location.href = 'editForm.php?parametros='+parametrosString; };
                boton1.onclick = function () { window.location.href = 'deleteForm.php?parametros=' + parametrosString; };                
                acciones.appendChild(boton);
                acciones.appendChild(boton1);
                
                row.appendChild(nombre);
                row.appendChild(apellido);
                row.appendChild(email);
                row.appendChild(telefono1);
                row.appendChild(telefono2);
                row.appendChild(telefono3);
                row.appendChild(acciones);
                tbody.appendChild(row);

            });

        })
        .catch(error => {

            console.error('Error', error);
        });

};

