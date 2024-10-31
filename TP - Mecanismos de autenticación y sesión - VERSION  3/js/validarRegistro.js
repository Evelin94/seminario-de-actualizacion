document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('formRegister').addEventListener('submit', validarRegistro);
});

function validarRegistro(event) {
    event.preventDefault();

    const usuario = document.getElementById('usuario').value;
    const contraseña = document.getElementById('contraseña').value;

    console.log(`Usuario: ${usuario}`);
    console.log(`Contraseña: ${contraseña}`);

    // VALIDACION DE LA CONTRASEÑA
    const condicionesContraseña = [
        { regex: /^[A-Za-z]/, description: 'Debe empezar con una letra' },
        { regex: /^.{8,16}$/, description: 'Debe tener entre 8 y 16 caracteres' },
        { regex: /[A-Z]/, description: 'Debe contener al menos una mayúscula' },
        { regex: /\d/, description: 'Debe contener al menos un número' },
        { regex: /[!@#$%^&*(),.?":{}|<>]/, description: 'Debe contener al menos un carácter especial' }
    ];

    let condicionesNoCumplidasContraseña = [];

    condicionesContraseña.forEach((condicion) => {
        if (!condicion.regex.test(contraseña)) {
            condicionesNoCumplidasContraseña.push(condicion.description);
        }
    });

    const mensajeErrorElementoContraseña = document.getElementById('mensajeErrorContraseña');
    if (condicionesNoCumplidasContraseña.length > 0) {
        mensajeErrorElementoContraseña.innerHTML = '<p style="font-size: 12px; text-align: left;">Por favor, corrija la contraseña:</p>';
        const listaCondicionesContraseña = document.createElement('ul');
        listaCondicionesContraseña.style.textAlign = 'left';
        listaCondicionesContraseña.style.fontSize = '10pt';

        condicionesNoCumplidasContraseña.forEach((condicion) => {
            const listItem = document.createElement('li');
            listItem.textContent = `${condicion}`;
            listaCondicionesContraseña.appendChild(listItem);
        });

        mensajeErrorElementoContraseña.appendChild(listaCondicionesContraseña);
        mensajeErrorElementoContraseña.style.display = 'block'; // Mostrar el mensaje de error
    } else {
        mensajeErrorElementoContraseña.style.display = 'none';
    }

    // VALIDACION DEL USUARIO
    const condicionesUsuario = [
        { regex: /^.{6,16}$/, description: 'Debe tener entre 6 y 16 caracteres' },
        { regex: /^[A-Za-z]/, description: 'Debe empezar con una letra' },
        { regex: /[A-Z]/, description: 'Debe contener al menos una mayúscula' }
    ];

    let condicionesNoCumplidasUsuario = [];

    condicionesUsuario.forEach((condicion) => {
        if (!condicion.regex.test(usuario)) {
            condicionesNoCumplidasUsuario.push(condicion.description);
        }
    });

    const mensajeErrorElementoUsuario = document.getElementById('mensajeErrorUsuario');
    if (condicionesNoCumplidasUsuario.length > 0) {
        mensajeErrorElementoUsuario.innerHTML = '<p style="font-size: 12px; text-align: left;">Por favor, corrija el usuario:</p>';
        const listaCondicionesUsuario = document.createElement('ul');
        listaCondicionesUsuario.style.textAlign = 'left';
        listaCondicionesUsuario.style.fontSize = '10pt';

        condicionesNoCumplidasUsuario.forEach((condicion) => {
            const listItem = document.createElement('li');
            listItem.textContent = `${condicion}`;
            listaCondicionesUsuario.appendChild(listItem);
        });

        mensajeErrorElementoUsuario.appendChild(listaCondicionesUsuario);
        mensajeErrorElementoUsuario.style.display = 'block'; // Mostrar el mensaje de error
    } else {
        mensajeErrorElementoUsuario.style.display = 'none';
    }

    // USUARIO Y CONTRASEÑA CUMPLEN TODAS LAS CONDICIONES     
    if (condicionesNoCumplidasUsuario.length === 0 && condicionesNoCumplidasContraseña.length === 0) {
        console.log('Enviar datos al backend');
        ingresarUsuario(usuario, contraseña);
    } else {
        console.log('Corregir errores');
    }
}


function ingresarUsuario(usuario, contraseña) {
    const url = 'backend/signup.php';

    const data = {
        usuario: usuario,
        contraseña: contraseña
    };

    const options = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    };

    fetch(url, options)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('respuesta del servidor', data.message);
            alert (data.message);
                     
        })
        .catch(error => {
            console.error('Error:', error);
            alert(error.message);       
           

        });
}
