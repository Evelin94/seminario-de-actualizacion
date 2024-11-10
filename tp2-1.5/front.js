function sendData(data) {
    console.log('Enviando datos:', data);

    
    fetch('create_user.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'  
        },
        body: JSON.stringify(data)  
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor: ' + response.status);
        }
        return response.json(); 
    })
    .then(data => {
        console.log('Respuesta del servidor:', data);

        
        if (data.success) {
            alert('Operación exitosa: ' + data.message);
        } else {
           
            alert('Error: ' + data.message);
        }

        
        document.getElementById('response-message').innerText = data.message;
    })
    .catch(error => {
        console.error('Error al enviar datos:', error);
       
        document.getElementById('response-message').innerText = 'Error: ' + error.message;
    });
}
