import socketio
from cryptography.fernet import Fernet
from flask import requests

class ClienteChat:
    def __init__(self):
        self.sio = socketio.Client()
        self.key = Fernet.generate_key()
        self.cipher = Fernet(self.key)

        self.setup_socket_events()

    def connect(self):
        self.sio.connect('http://localhost:5000')

    def setup_socket_events(self):
        @self.sio.event
        def connect():
            print('Conectado al servidor')

        @self.sio.event
        def disconnect():
            print('Desconectado del servidor')

        @self.sio.event
        def message(data):
            decrypted_msg = self.cipher.decrypt(data.encode('utf-8')).decode('utf-8')
            print(f"Mensaje recibido: {decrypted_msg}")

    def register(self, username, password):
        response = requests.post('http://localhost:5000/register', json={'username': username, 'password': password})
        print(response.json())

    def login(self, username, password):
        response = requests.post('http://localhost:5000/login', json={'username': username, 'password': password})
        if response.status_code == 200:
            self.sio.emit('join', {'username': username})
            return True
        else:
            print(response.json())
            return False

    def send_message(self, message):
        encrypted_msg = self.cipher.encrypt(message.encode('utf-8')).decode('utf-8')
        self.sio.send({'message': encrypted_msg})


if __name__ == '__main__':
    cliente = ClienteChat()
    cliente.connect()

    username = input('Usuario: ')
    password = input('Contraseña: ')
    action = input('¿Deseas registrarte o iniciar sesión? (r/l): ')

    if action == 'r':
        cliente.register(username, password)
    elif action == 'l':
        if cliente.login(username, password):
            while True:
                msg = input('Escribe tu mensaje: ')
                cliente.send_message(msg)
