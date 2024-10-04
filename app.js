let username = '';
const ws = new WebSocket('ws://localhost:8080');

ws.onopen = function() {
    console.log('Conectado al servidor WebSocket');
};

ws.onmessage = function(event) {
   //aca es donde decifro el msj 
    const decryptedMessage = decryptMessage(event.data);
    document.getElementById('messages').innerHTML += '<div style="color: green;">' + decryptedMessage + '</div>';
    console.log('Mensaje recibido: ' + decryptedMessage);
};

document.getElementById('login-button').onclick = function() {
    username = document.getElementById('username').value;
    if (username) {
        document.getElementById('login-form').style.display = 'none';
        document.getElementById('chat').style.display = 'block';
        console.log('Usuario conectado: ' + username);
    }
};

document.getElementById('send-button').onclick = function() {
    const mensaje = document.getElementById('message-input').value;
    if (mensaje) {
        const encryptedMessage = encryptMessage(`${username}: ${mensaje}`);
        ws.send(encryptedMessage);
        document.getElementById('messages').innerHTML += '<div style="color: blue;">' + username + ': ' + mensaje + '</div>';
        document.getElementById('message-input').value = '';  
    }
};

ws.onclose = function() {
    console.log('Conexion cerrada');
};

function encryptMessage(message) {
    const key = CryptoJS.enc.Utf8.parse("mi_clave_secreta"); 
    const encrypted = CryptoJS.AES.encrypt(message, key, {
        mode: CryptoJS.mode.ECB,
        padding: CryptoJS.pad.Pkcs7
    });
    return encrypted.toString(); 
}

function decryptMessage(ciphertext) {
    const key = CryptoJS.enc.Utf8.parse("mi_clave_secreta"); 
    const decrypted = CryptoJS.AES.decrypt(ciphertext, key, {
        mode: CryptoJS.mode.ECB,
        padding: CryptoJS.pad.Pkcs7
    });
    return decrypted.toString(CryptoJS.enc.Utf8); 
}
