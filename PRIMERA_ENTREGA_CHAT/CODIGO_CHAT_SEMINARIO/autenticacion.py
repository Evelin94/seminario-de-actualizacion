import mysql.connector
import bcrypt

class Autenticacion:
    def __init__(self, host, user, password, database):
        # Conexión a la base de datos
        self.conn = mysql.connector.connect(
            host='localhost',
            user='root',
            password='',
            database='botlogin'
        )
        self.cursor = self.conn.cursor()

    def registrar_usuario(self, username, plain_password):
        # Hashear la contraseña antes de guardarla
        hashed_password = bcrypt.hashpw(plain_password.encode('utf-8'), bcrypt.gensalt())

        # Verificar si el usuario ya existe
        sql_check = "SELECT usuario FROM usuarios WHERE usuario = %s"
        self.cursor.execute(sql_check, (username,))
        result = self.cursor.fetchone()
        
        if result:
            raise Exception("El usuario ya existe")

        # Insertar el nuevo usuario en la base de datos
        sql = "INSERT INTO usuarios (usuario, password) VALUES (%s, %s)"
        self.cursor.execute(sql, (username, hashed_password))
        self.conn.commit()

    def cerrar_conexion(self):
        # Cerrar la conexión a la base de datos
        self.cursor.close()
        self.conn.close()
