document.addEventListener('DOMContentLoaded', function () {

    document.getElementById('searchForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const positionId = document.getElementById('idpuesto').value;
       
        fetch('obtener_puesto.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ positionId: positionId })
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
                    const titles = ['Id Puesto', 'Usuario', 'Grupo']; // Títulos de las columnas
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

                    // Fetch para obtener usuarios
                    fetch('getUsuarioPuesto.php')
                        .then(response => response.json())
                        .then(datos => {
                           
                            if (datos.error) {
                                console.error('Error en el servidor:', datos.error);
                            } else {
                                
                                // Obtener el select
                                var select = document.getElementById("selectusuario");

                                // Limpiar opciones existentes
                                select.innerHTML = "";

                                // Recorrer los datos y agregar las opciones
                                datos.forEach(function (usuario, index) {
                                    var option = document.createElement("option");
                                    option.value = usuario.idUsuarios;
                                    option.text = usuario.usuario;
                                    select.add(option);
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching data:', error);
                        });

                    // Fetch para obtener grupos
                    fetch('getGrupoPuesto.php')
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
                                datos.forEach(function (usuario, index) {
                                    var option = document.createElement("option");
                                    option.value = usuario.idGrupos;
                                    option.text = usuario.Grupo;
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
                alert('Error al obtener los datos del usuario');
            });
    });

    // Recupera los valores del usuario y la contraseña desde localStorage
    const sessionidusuario = localStorage.getItem('idusuario');
    const sessionusuario = localStorage.getItem('usuario');
    const sessionpassword = localStorage.getItem('contraseña');
    console.log(sessionidusuario, sessionusuario, sessionpassword);

    // Realiza la solicitud JSON al archivo PHP con método POST y headers personalizados
    fetch('../verificarPermiso.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Idusuario': sessionidusuario,
            'X-Usuario': sessionusuario,
            'X-Password': sessionpassword,
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

});


document.getElementById('updateForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const positionId = document.getElementById('idpuesto').value;
    const userId = document.getElementById('selectusuario').value;
    const groupId = document.getElementById('selectgrupo').value;

    console.log(positionId);
    console.log(userId);
    console.log(groupId);

    fetch('actualizado_puesto.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ positionId: positionId, userId: userId, groupId: groupId })
    })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
            } else {
                alert('Puesto actualizado correctamente');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al actualizar los datos del puesto');
        });

});


document.getElementById('backButton').addEventListener('click', function () {
    window.location.href = '../dashboard.html';
});




