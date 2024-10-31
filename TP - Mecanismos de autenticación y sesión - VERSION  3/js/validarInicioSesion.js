window.addEventListener('DOMContentLoaded', function () {
    document.getElementById('formInicio').addEventListener('submit', function (event) {
        event.preventDefault(); // Evitar que el formulario se envíe normalmente

        // OBTENER LOS DATOS DEL FORMULARIO Y ELIMINAR ESPACIOS EN BLANCO
        var usuario = document.getElementById('usuario').value.trim();
        var contraseña = document.getElementById('contraseña').value.trim();

        // VALIDACION PARA EVITAR CAMPOS VACIOS
        if (usuario === '' || contraseña === '') {
            alert('Por favor, complete todos los campos');
            return; // Detener el flujo si hay campos vacíos
        }

        // Crear objeto de datos a enviar
        var datos = {
            usuario: usuario,
            contraseña: contraseña
        };

        //console.log(datos);

        // Realizar petición JSON
        fetch('backend/login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(datos)
        })
            .then(function (response) {
                if (response.ok) {
                    return response.json();
                } else {
                    throw new Error('La solicitud no fue exitosa');
                }
            })
            .then(function (data) {
                if (data.success) {    
                    console.log(data);
                    // Almacenar datos en local storage
                    localStorage.setItem('idusuario', data.idUsuario);
                    localStorage.setItem('usuario', data.usuario);
                    localStorage.setItem('contraseña', data.contraseña);       
                    localStorage.setItem('token', data.token);  
                    window.location.href = 'backend/dashboard.php';
                    
                }else
                {
                    // Error de login
                    alert(data.message); // Mostrar mensaje de error al usuario
                    resetearFormulario(); // Resetear el formulario
                }
            })
            .catch(function (error) {
                console.error(error);
                alert('Error: \n' + error.message);
            });

    });
});


function resetearFormulario() {
    // Get the form element
    var form = document.getElementById('formInicio');

    // Reset form fields
    form.usuario.value = '';
    form.contraseña.value = '';
   
}