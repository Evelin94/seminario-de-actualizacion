document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('searchForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const permisoId = document.getElementById('idpermiso').value;
        console.log(permisoId);
        fetch('obtener_permiso.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ permisoId: permisoId })
        })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                } else {
                   
                    // Crea la tabla
                    const table = document.createElement('table');
                    table.classList.add('table'); // Agrega clases de Bootstrap si las estás utilizando

                    // Crea la cabecera de la tabla
                    const thead = document.createElement('thead');
                    const headerRow = document.createElement('tr');
                    const titles = ['Id Permiso', 'Grupo', 'Acciones']; // Títulos de las columnas
                    titles.forEach(title => {
                        const th = document.createElement('th');
                        th.textContent = title;
                        headerRow.appendChild(th);
                    });
                    thead.appendChild(headerRow);
                    table.appendChild(thead);

                    // Crea las filas de datos
                    const tbody = document.createElement('tbody');
                    const row = document.createElement('tr');
                    Object.values(data).forEach(value => {
                        const td = document.createElement('td');
                        td.textContent = value;
                        row.appendChild(td);
                    });
                    tbody.appendChild(row);
                    table.appendChild(tbody);

                    // Inserta la tabla en el div con id 'listado'
                    const listadoDiv = document.getElementById('listado');
                    listadoDiv.innerHTML = ''; // Limpia el contenido previo
                    listadoDiv.appendChild(table);
                    
                    document.getElementById('searchForm').style.display = 'none';
                    document.getElementById('updateForm').style.display = 'block';

                    // Fetch para obtener Grupos
                    fetch('getGrupoPermiso.php')
                        .then(response => response.json())
                        .then(datos => {
                           
                            if (datos.error) {
                                console.error('Error en el servidor:', datos.error);
                            } else {
                                
                                // Obtener el select
                                var select = document.getElementById("selectgrupo");

                                // Limpiar opciones existentes
                                select.innerHTML = "";

                                // Recorrer los datos y agregar las opciones
                                datos.forEach(function ( grupo, index) {
                                    var option = document.createElement("option");
                                    option.value = grupo.idGrupos;
                                    option.text = grupo.Grupo;
                                    select.add(option);
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching data:', error);
                        });

                    // Fetch para obtener Acciones
                    fetch('getAccionPermiso.php')
                        .then(response => response.json())
                        .then(datos => {
                          
                            if (datos.error) {
                                console.error('Error en el servidor:', datos.error);
                            } else {

                                // Obtener el select
                                var select = document.getElementById("selectaccion");

                                // Limpiar opciones existentes
                                select.innerHTML = "";

                                // Recorrer los datos y agregar las opciones
                                datos.forEach(function (accion, index) {
                                    var option = document.createElement("option");
                                    option.value = accion.idAcciones;
                                    option.text = accion.Accion;
                                    select.add(option);
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching data:', error);
                        });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al obtener los datos del permiso');
            });
    });
});


document.getElementById('updateForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const permisoId = document.getElementById('idpermiso').value;
    const groupId = document.getElementById('selectgrupo').value;
    const actionId = document.getElementById('selectaccion').value;
   
     console.log(groupId);
     console.log(actionId);

    fetch('actualizado_permiso.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ permisoId:permisoId, groupId: groupId, actionId: actionId })
    })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
            } else {
                alert('Permiso actualizado correctamente');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al actualizar los datos del permiso');
        });
});


document.getElementById('backButton').addEventListener('click', function () {
    window.location.href = '../index.html';
});




