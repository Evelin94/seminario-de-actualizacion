import { getAuthHeaders } from '../authHeaders';
import { checkSessionStatus } from '../sessionTimer';

const sessionidusuario = localStorage.getItem('idusuario');
const sessionusuario = localStorage.getItem('usuario');
const sessionpassword = localStorage.getItem('contraseña');
const sessiontoken = localStorage.getItem('token');
const sessionMetodo = localStorage.getItem('selectedAuthMethod');

document.addEventListener('DOMContentLoaded', function () {

    // VERIFICAR QUE LA SESION NO HAYA EXPIRADO
    checkSessionStatus();

    // Realiza la solicitud JSON al archivo PHP con método POST y headers personalizados
    fetch('../verificarPermiso.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Idusuario': sessionidusuario,
            'X-Usuario': sessionusuario,
            'X-Token': sessiontoken,
            'X-Operacion': '1' // LECTURA
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
                console.log('Listado:', data.message);
                listado();


            } else {
                console.log('Usuario no autorizado:', data.message);
                alert('No esta autorizado para ejecutar esta accion.')
            }

        })
        .catch(error => {
            console.error('Error al obtener los datos:', error);
        });

    document.getElementById('backButton').addEventListener('click', function () {
        window.location.href = '../dashboard.html';
    });


function listado() {

    const headerSelect = getAuthHeaders('1'); // LECTURA
    console.log('headerSelect envio', headerSelect);

// Realiza la solicitud JSON al archivo PHP
    fetch('listado_puesto.php', {
        method: 'POST',
        headers: headerSelect
    })

    .then(response => response.json())


    .then(data => {

        // Verifica si hay un error en la respuesta
        if (data.error) {
            console.error('Error al obtener los datos:', data.error);
            return;
        }

        if (sessionMetodo === 'token') {
            // ALMACENAR NUEVO TOKEN
            console.log('New token : ', data.token);
            localStorage.setItem('token', data.token);
        }

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
        data.data.forEach((item, index) => {
            const row = document.createElement('tr');
            Object.values(item).forEach(value => {
                const td = document.createElement('td');
                td.textContent = value;
                row.appendChild(td);
            });
            
            tbody.appendChild(row);
        });
        table.appendChild(tbody);

        // Inserta la tabla en el div con id 'listado'
        const listadoDiv = document.getElementById('listado');
        listadoDiv.innerHTML = ''; // Limpia el contenido previo
        listadoDiv.appendChild(table);
    })
    .catch(error => {
        console.error('Error al obtener los datos:', error);
    });
    
}

   
})