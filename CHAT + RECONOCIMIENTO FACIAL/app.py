# RECONOCIMIENTO FACIAL

from flask import Flask, render_template, request, redirect, session, url_for, flash
from flask_socketio import SocketIO, join_room, emit, disconnect
from cryptography.fernet import Fernet
import pymysql
import bcrypt
import os
import base64
from werkzeug.utils import secure_filename
from datetime import datetime
import cv2
import face_recognition 
import numpy as np

app = Flask(__name__)
app.secret_key = 'tu_clave_secreta_aqui'  # Cambia esta clave por una más segura
socketio = SocketIO(app)

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
    username = request.form['username']
    password = request.form['password']

    conn = connect_db()
    try:
        with conn.cursor() as cursor:
            cursor.execute("SELECT * FROM usuarios WHERE usuario = %s", (username,))
            user = cursor.fetchone()

            if user:  # Si el usuario existe
                stored_password_hash = user[2]

                if bcrypt.checkpw(password.encode('utf-8'), stored_password_hash.encode('utf-8')):
                    session['image_path'] = user[4]  # Guardar la ruta de la imagen en la sesión
                    session['username'] = user[1]  # Guardar el nombre de usuario en la sesión
                    return redirect(url_for('recognition'))
                else:
                    flash('Contraseña incorrecta', 'danger')
            else:
                flash('Usuario no encontrado', 'danger')

    except pymysql.MySQLError as e:
        flash('Error al verificar el usuario', 'danger')
        print(f"Error: {e}")
    finally:
        conn.close()

    return redirect(url_for('login'))

@app.route('/register', methods=['GET', 'POST'])
def register():
    if request.method == 'POST':
        username = request.form['usuario']
        password = request.form['password']
        email = request.form['email']
        photo_name = request.form['photoName']
        photo_data = request.form['photoData']

        if username and password and email and photo_name:
            photo_data = photo_data.split(',')[1]
            photo_bytes = base64.b64decode(photo_data)

            photo_filename = photo_name
            photo_path = os.path.join(app.config['REGISTER_FOLDER'], photo_filename)
            with open(photo_path, 'wb') as f:
                f.write(photo_bytes)

            if len(username) < 3 or len(password) < 6:
                flash('El nombre de usuario o la contraseña son demasiado cortos', 'danger')
                return redirect(url_for('register'))

            conn = connect_db()
            try:
                with conn.cursor() as cursor:
                    cursor.execute("SELECT * FROM usuarios WHERE usuario = %s", (username,))
                    existing_user = cursor.fetchone()
                    if existing_user:
                        flash('El nombre de usuario ya existe', 'danger')
                        return redirect(url_for('register'))

                    password_hash = bcrypt.hashpw(password.encode('utf-8'), bcrypt.gensalt()).decode('utf-8')

                    cursor.execute("INSERT INTO usuarios (usuario, password, email, image_path) VALUES (%s, %s, %s, %s)", 
                                   (username, password_hash, email, photo_filename))
                    conn.commit()
                    flash('Usuario registrado exitosamente', 'success')

            except pymysql.MySQLError as e:
                flash('Error al registrar el usuario', 'danger')
                print(f"Error: {e}")
            finally:
                conn.close()

            return redirect(url_for('register'))

    return render_template('register.html')

@app.route('/recognition')
def recognition():
    return render_template('reconocimientofacial.html')

@app.route('/verifyphoto', methods=['POST'])
def verify_user():
    photo_data = request.form.get('photoData')
    photo_name = request.form.get('photoName')

    if not photo_data or not photo_name:
        flash('No se recibió ninguna imagen', 'danger')
        return redirect(url_for('index'))

    try:
        img_data = base64.b64decode(photo_data.split(',')[1])
        save_path = os.path.join(app.config['LOGIN_FOLDER'], photo_name)

        with open(save_path, 'wb') as f:
            f.write(img_data)

        session_image_path = session.get('image_path')
        if session_image_path:
            comparison_image_path = os.path.join(app.config['REGISTER_FOLDER'], session_image_path)

            uploaded_image = face_recognition.load_image_file(save_path)
            registered_image = face_recognition.load_image_file(comparison_image_path)

            uploaded_encoding = face_recognition.face_encodings(uploaded_image)[0]
            registered_encoding = face_recognition.face_encodings(registered_image)[0]

            results = face_recognition.compare_faces([registered_encoding], uploaded_encoding)
            
            if results[0]:
                flash('Las imágenes son iguales', 'success')
                return redirect(url_for('chat'))
            else:
                flash('Las imágenes no coinciden', 'danger')
                return redirect(url_for('index'))
        else:
            flash('No se encontró la imagen de referencia en la sesión', 'warning')

    except Exception as e:
        flash(f'Error al procesar la imagen: {str(e)}', 'danger')

@app.route('/chat')
def chat():
    if 'username' not in session:
        return redirect(url_for('login'))
    username = session['username']
    return render_template('chat.html', username=username)

@app.route('/logout')
def logout():
    if 'username' in session:
        session.pop('username', None)
        flash('Has cerrado sesión exitosamente.', 'success')
    else:
        flash('No estás autenticado.', 'danger')

    return redirect(url_for('index'))

@socketio.on('connect')
def handle_connect():
    username = session.get('username')
    if username:
        join_room(room_global)
        print(f"{username} se ha unido a la sala global")
        emit('clave', key, room=room_global)
        emit('mensaje', {'message': f"{username} se ha unido al chat."}, room=room_global)
    else:
        disconnect()

@socketio.on('message')
def handle_message(data):
    encrypted_message = data['encryptedMessage']
    username = session.get('username')
    print(f"Mensaje recibido cifrado de {username}: {encrypted_message}")
    emit('message', {'encryptedMessage': encrypted_message, 'username': username}, room=room_global, include_self=False)

@socketio.on('disconnect')
def handle_disconnect():
    username = session.get('username')
    if username:
        print(f"{username} ha salido de la sala")
        emit('mensaje', {'message': f"{username} ha salido del chat."}, room=room_global)

if __name__ == '__main__':
    socketio.run(app, host='localhost', port=5000)
