

from flask import Flask, render_template, request, redirect, session, url_for, flash
from flask_socketio import SocketIO, join_room, emit, disconnect
from cryptography.fernet import Fernet
import pymysql
import bcrypt
import os
import base64
from werkzeug.utils import secure_filename
from datetime import datetime
import requests
import smtplib
from email.mime.text import MIMEText
from email.mime.multipart import MIMEMultipart
import random


app = Flask(__name__)
app.secret_key = 'tu_clave_secreta_aqui'  # Cambia esta clave por una más segura
socketio = SocketIO(app)
app.config.from_object('config.Config')
# Diccionario temporal para almacenar códigos de verificación
verification_codes = {}
app.secret_key = '6LfWvF8qAAAAAIkEze0-PRij1ads9FespMphFZbT'  # Clave google


# Generar una clave de cifrado (simétrica)
key = Fernet.generate_key().decode()  # Convertimos la clave a string
room_global = "sala_unica"  # Nombre de la sala compartida

# Configuración de la base de datos
db_config = {
    'host': 'localhost',
    'user': 'root',
    'password': '',
    'database': 'botlogin',
    'port': 3306
}

# Conexión a la base de datos
def connect_db():
    """Establece la conexión con la base de datos."""
    return pymysql.connect(**db_config)


# Definir las carpetas para registro y login
UPLOAD_FOLDER = 'static/uploads'
REGISTER_FOLDER = os.path.join(UPLOAD_FOLDER, 'registro')
LOGIN_FOLDER = os.path.join(UPLOAD_FOLDER, 'login')

# Configurar las carpetas en la aplicación Flask
app.config['REGISTER_FOLDER'] = REGISTER_FOLDER
app.config['LOGIN_FOLDER'] = LOGIN_FOLDER

# Crear las carpetas si no existen
if not os.path.exists(REGISTER_FOLDER):
    os.makedirs(REGISTER_FOLDER)

if not os.path.exists(LOGIN_FOLDER):
    os.makedirs(LOGIN_FOLDER)


@app.route('/')
def index():
    return render_template('login.html')

@app.route('/login', methods=['GET', 'POST'])
def login():
    if request.method == 'POST':
        username = request.form.get('username')
        password = request.form.get('password')

        recaptcha_response = request.form.get('g-recaptcha-response')

        if not validate_recaptcha(recaptcha_response):
            flash('Por favor completa el captcha correctamente.', 'danger')
            return redirect(url_for('login'))

        user = fetch_user(username)
        if user and bcrypt.checkpw(password.encode('utf-8'), user[2].encode('utf-8')):
            session['username'] = user[1]
            session['email'] = user[3]
            return redirect(url_for('codigosms'))
        else:
            flash('Usuario o contraseña incorrectos.', 'danger')
            return redirect(url_for('login'))

    return render_template('login.html')

def validate_recaptcha(response):
    payload = {
        'secret': '6LfWvF8qAAAAAIkEze0-PRij1ads9FespMphFZbT',
        'response': response
    }
    return requests.post('https://www.google.com/recaptcha/api/siteverify', data=payload).json().get('success')

def fetch_user(username):
    conn = connect_db()
    try:
        with conn.cursor() as cursor:
            cursor.execute("SELECT * FROM usuarios WHERE usuario = %s", (username,))
            return cursor.fetchone()
    except pymysql.MySQLError as e:
        flash('Error al verificar el usuario', 'danger')
        print(f"Error: {e}")
    finally:
        conn.close()

@app.route('/register', methods=['GET', 'POST'])
def register():
    if request.method == 'POST':
        username = request.form['usuario']
        password = request.form['password']
        email = request.form['email']
      
        if username and password :
            # Validar longitud de usuario y contraseña
            if len(username) < 3 or len(password) < 6:
                flash('El nombre de usuario o la contraseña son demasiado cortos', 'danger')
                return redirect(url_for('register'))

            # Verificar si el usuario ya existe
            conn = connect_db()
            try:
                with conn.cursor() as cursor:
                    cursor.execute("SELECT * FROM usuarios WHERE usuario = %s", (username,))
                    existing_user = cursor.fetchone()
                    if existing_user:
                        flash('El nombre de usuario ya existe', 'danger')
                        return redirect(url_for('register'))

                    # Hashear la contraseña antes de almacenarla
                    password_hash = bcrypt.hashpw(password.encode('utf-8'), bcrypt.gensalt()).decode('utf-8')

                    # Insertar el nuevo usuario en la base de datos
                    cursor.execute("INSERT INTO usuarios (usuario, password, email) VALUES (%s, %s, %s)", (username, password_hash, email))
                    conn.commit()
                    flash('Usuario registrado exitosamente', 'success')

            except pymysql.MySQLError as e:
                flash('Error al registrar el usuario: ' + str(e), 'danger')
                print(f"Error: {e}")
            finally:
                conn.close()
           
            return redirect(url_for('register'))

    return render_template('register.html')


@app.route('/codigosms')
def codigosms():

   email = session['email']
    
   # Generar un código de verificación de 6 dígitos
   code = random.randint(100000, 999999)
    
    # Almacenar el código temporalmente (puedes guardarlo en la base de datos)
   verification_codes[email] = code
    
    # Enviar el código por correo electrónico
   send_email(email, code)
    
   
   return render_template('codigosms.html', email=email)


def send_email(recipient, code):
    # Configurar la información del correo electrónico
    sender_email = app.config['MAIL_USERNAME']
    sender_password = app.config['MAIL_PASSWORD']
    smtp_server = app.config['MAIL_SERVER']
    smtp_port = app.config['MAIL_PORT']

    # Crear el cuerpo del correo
    msg = MIMEMultipart()
    msg['From'] = sender_email
    msg['To'] = recipient
    msg['Subject'] = 'Your Verification Code'
    body = f'Your verification code is: {code}'
    msg.attach(MIMEText(body, 'plain'))

    try:
        # Conectar al servidor SMTP
        server = smtplib.SMTP(smtp_server, smtp_port)
        server.starttls()  # Iniciar la conexión TLS
        server.login(sender_email, sender_password)
        
        # Enviar el correo
        text = msg.as_string()
        server.sendmail(sender_email, recipient, text)
        server.quit()
        print(f"Email sent to {recipient}")
    except Exception as e:
        print(f"Error sending email: {e}")


@app.route('/verify/<email>', methods=['GET', 'POST'])
def verify(email):
    if request.method == 'POST':
        input_code = request.form['code']

        # Imprimir los códigos comparados
        print(f"Code entered by user: {input_code}")
        print(f"Stored verification code: {verification_codes[email]}")
              
        if int(input_code) == int(verification_codes[email]):
            print(f"Son iguales")
            # Aquí puedes autenticar al usuario o redirigir a otra página
            return redirect(url_for('chat'))
        else:
            print(f"No Son iguales")
            flash('Invalid code, please try again', 'danger')
            return redirect(url_for('codigosms', email=email))


@app.route('/chat')
def chat():
    if 'username' not in session:  # Verifica si el usuario está autenticado
        return redirect(url_for('index'))  # Si no, redirige al login
    username = session['username']
    return render_template('chat.html', username=username)

@app.route('/logout')
def logout():
    # Verifica si el usuario está autenticado
    if 'username' in session:
        session.pop('username', None)  # Elimina la sesión del usuario
        flash('Has cerrado sesión exitosamente.', 'success')
    else:
        flash('No estás autenticado.', 'danger')

    return redirect(url_for('index'))  # Redirige a la página de inicio de sesión


@socketio.on('connect')
def handle_connect():
    username = session.get('username')
    if username:  # Verifica si el usuario está autenticado
        join_room(room_global)  # Unir a todos los usuarios a la misma sala
        print(f"{username} se ha unido a la sala global")
        emit('clave', key, room=room_global)  # Enviamos la clave a todos los clientes conectados en la sala
        emit('mensaje', {'message': f"{username} se ha unido al chat."}, room=room_global)
    else:
        disconnect()  # Desconectar al usuario si no está autenticado


@socketio.on('message')
def handle_message(data):
    encrypted_message = data['encryptedMessage']
    username = session.get('username')
    print(f"Mensaje recibido cifrado de {username}: {encrypted_message}")
    # Enviar el mensaje cifrado y el nombre del usuario a todos en la sala, excepto al emisor
    emit('message', {'encryptedMessage': encrypted_message, 'username': username}, room=room_global, include_self=False)
    
@socketio.on('disconnect')
def handle_disconnect():
    username = session.get('username')
    if username:
        print(f"{username} ha salido de la sala")
        emit('mensaje', {'message': f"{username} ha salido del chat."}, room=room_global)

if __name__ == '__main__':
    socketio.run(app, host='localhost', port=5000)
