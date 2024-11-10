CREATE DATABASE agenda_contactos;
USE agenda_contactos;

CREATE TABLE contactos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    direccion VARCHAR(255)
);

CREATE TABLE telefonos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_contacto INT,
    numero VARCHAR(20),
    FOREIGN KEY (id_contacto) REFERENCES contactos(id) ON DELETE CASCADE
);


INSERT INTO contactos (nombre, email, direccion) VALUES
('Eve Oliva', 'eve@example.com', 'Calle 453'),
('francesco Tomaselli', 'fran@example.com', 'Punta Mogotes');

INSERT INTO telefonos (id_contacto, numero) VALUES
(1, '123456789'),
(1, '987654321'),
(2, '555555555');
