from flask import Flask, request, render_template, redirect, url_for, flash
from autenticacion import Autenticacion

app = Flask(__name__)
app.secret_key = 'supersecreto'  # Necesario para manejar mensajes flash

# Conexión a la base de datos
auth = Autenticacion(host="localhost", user="root", password="", database="botlogin")

@app.route('/')
def index():
    return render_template('index.html')

@app.route('/registrar', methods=['GET', 'POST'])
def registrar():
    if request.method == 'POST':
        # Recuperar usuario y contraseña del formulario
        username = request.form['usuario']
        password = request.form['contraseña']
        
        # Intentar registrar usuario
        try:
            auth.registrar_usuario(username, password)
            flash("Registro exitoso", "success")
            return redirect(url_for('index'))  # Redirige al login
        except Exception as e:
            flash(f"Error al registrar usuario: {str(e)}", "error")
            return redirect(url_for('registrar'))  # Redirige al registro

    return render_template('register.html')

if __name__ == '__main__':
    app.run(debug=True)
