export function checkSessionStatus() {
    // Realiza la solicitud fetch
    fetch('../sessionTime.php', {
        method: 'POST', // O 'GET' si tu PHP maneja GET
        headers: {
            'Content-Type': 'application/json',
            // Agrega otras cabeceras si es necesario
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'expired') {
                alert(data.message);
                // Redirigir al usuario a index.html
                window.location.href = 'http://localhost/controlacceso3/index.html';
            } else if (data.status === 'active') {
                // Aquí puedes manejar el caso en que la sesión sigue activa, si es necesario
                console.log('La sesion sigue activa. Segundos transcurridos desde la ultima actividad:', data.tiempo);
            } 
        })
        .catch(error => {
            console.error('Error:', error);
        });
}
