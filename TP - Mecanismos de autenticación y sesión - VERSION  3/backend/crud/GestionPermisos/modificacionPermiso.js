import { checkSessionStatus } from '../sessionTimer';

document.addEventListener('DOMContentLoaded', function () {

    // VERIFICAR QUE LA SESION NO HAYA EXPIRADO
    checkSessionStatus();

    // Recupera los valores del usuario y la contraseña desde localStorage
    const sessionidusuario = localStorage.getItem('idusuario');
    const sessionusuario = localStorage.getItem('usuario');
    const sessionpassword = localStorage.getItem('contraseña');
    const sessiontoken = localStorage.getItem('token');
    console.log(sessionidusuario, sessionusuario, sessionpassword, sessiontoken);

    // Realiza la solicitud JSON al archivo PHP con método POST y headers personalizados
    fetch('../verificarPermiso.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Idusuario': sessionidusuario,
            'X-Usuario': sessionusuario,
            'X-Token': sessiontoken,
            'X-Operacion': '4' // ACTUALIZACION
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al obtener los datos');
            }
            return response.json();
        })
        .then(data => {
            console.log(data);
            // Verifica si hay un error en la respuesta
            if (data.error) {
                console.error('Error:', data.error);
                return;
            }

            if (data.success) {
                console.log('Actualizacion:', data.message);
                document.getElementById('searchForm').style.display = 'block';

            } else {
                console.log('Usuario no autorizado:', data.message);
                alert('No esta autorizado para ejecutar esta accion.')
            }

        })
        .catch(error => {
            console.error('Error al obtener los datos:', error);
        });


    document.getElementById('searchForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const permisoId = document.getElementById('idpermiso').value;
        console.log(permisoId);
        const sessiontoken = localStorage.getItem('token');
        fetch('obtener_permiso.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Token': sessiontoken
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
                    const sessiontoken = localStorage.getItem('token');
                    
                    // Fetch para obtener Grupos
                    fetch('getGrupoPermiso.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Token': sessiontoken,
                        }})
                    
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
                    fetch('getAccionPermiso.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Token': sessiontoken,
                        }})

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
    const sessiontoken = localStorage.getItem('token');
     console.log(groupId);
     console.log(actionId);
     console.log(sessiontoken);
    fetch('actualizado_permiso.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Token': sessiontoken,
        },
        body: JSON.stringify({ permisoId:permisoId, groupId: groupId, actionId: actionId })
    })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
            } else {
                alert('Permiso actualizado correctamente');
                // Store new token
                console.log('New token:', data.token);
                localStorage.setItem('token', data.token);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al actualizar los datos del permiso');
        });
});


document.getElementById('backButton').addEventListener('click', function () {
    window.location.href = '../dashboard.html';
});




