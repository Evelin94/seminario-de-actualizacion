window.addEventListener('DOMContentLoaded', function () {
    document.getElementById('formConsulta').addEventListener('submit', function (event) {
        event.preventDefault(); // Evitar que el formulario se envíe normalmente

        // Obtener los datos del formulario
        var usuario = document.getElementById('usuario').value;
        var contraseña = document.getElementById('contraseña').value;

        // Crear objeto de datos a enviar
        var datos = {
            usuario: usuario,
            contraseña: contraseña
        };

        console.log(datos);

        // Realizar petición JSON
        fetch('acceso.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(datos)
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('La solicitud no fue exitosa');
                }
                return response.json();
            })
            .then(data => {
                if (data.hasOwnProperty('mensaje')) {
                    // Se recibió un mensaje de error desde el servidor
                    throw new Error(data.mensaje);
                }

                // Construir URL con parámetros de las acciones, usuario y grupo
                var accionesParams = data.acciones.map((accion, index) => `accion${index + 1}=${encodeURIComponent(accion)}`).join('&');
                var urlParams = `usuario=${encodeURIComponent(usuario)}&grupo=${encodeURIComponent(data.grupo.Grupo)}&${accionesParams}`;

                // Redirigir según el grupo
                if (data.grupo.Grupo === 'Cliente') {
                    window.location.href = `cliente.php?${urlParams}`;
                } else if (data.grupo.Grupo === 'Verdulero') {
                    window.location.href = `verdulero.php?${urlParams}`;
                } else {
                    throw new Error('Grupo no esperado');
                }
            })
            .catch(error => {
                console.error('Error:', error.message);
                alert('Error: ' + error.message); // Mostrar mensaje de error
            });
    });
});
