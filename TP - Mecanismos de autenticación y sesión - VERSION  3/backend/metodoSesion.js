
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("backButton").addEventListener("click", function (event) {
            event.preventDefault();

            // Obtener el valor del radio button seleccionado
            const selectedAuthMethod = document.querySelector('input[name="authMethod"]:checked').value;
            console.log("Método de autenticación seleccionado: " + selectedAuthMethod);

            // Guardar el valor en localStorage
            localStorage.setItem('selectedAuthMethod', selectedAuthMethod);



            // Enviar el método de autenticación seleccionado a metodoSesion.php usando fetch
            fetch('metodoSesion.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authentication-Method': selectedAuthMethod
                },
                body: JSON.stringify({ authMethod: selectedAuthMethod })
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Respuesta del servidor:', data);
                    // Redirigir a la URL específica después de enviar los datos
                    window.location.href = "./crud/dashboard.html";
                })
                .catch(error => {
                    console.error('Error:', error);
                });
  
          
        });
        });
