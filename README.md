# API Flight Login

Este proyecto es una API RESTful desarrollada en PHP utilizando el micro-framework Flight. Permite gestionar usuarios en una base de datos MySQL, proporcionando endpoints para operaciones CRUD (crear, leer, actualizar y eliminar usuarios).

## Requisitos

- PHP 7.2 o superior
- Composer
- MySQL
- WAMP, XAMPP o similar (en Windows)

## Instalación

1. Clona o descarga este repositorio en tu servidor local.
2. Instala las dependencias ejecutando en la raíz del proyecto:
   ```
   composer install
   ```
3. Crea una base de datos llamada `login` en MySQL y una tabla `usuario` con la siguiente estructura:

   ```sql
   CREATE TABLE usuario (
     id INT AUTO_INCREMENT PRIMARY KEY,
     nombre VARCHAR(100) NOT NULL,
     apellido VARCHAR(100) NOT NULL,
     email VARCHAR(100) NOT NULL,
     password VARCHAR(255) NOT NULL
   );
   ```

4. Configura las credenciales de la base de datos en el archivo `index.php` si es necesario.

## Uso

La API expone los siguientes endpoints:

### Obtener todos los usuarios

- **GET** `/usuarios`
- Respuesta: Lista de usuarios.

### Obtener un usuario por ID

- **GET** `/usuarios/{id}`
- Respuesta: Datos del usuario.

### Crear un usuario

- **POST** `/usuarios`
- Body (JSON):
  ```json
  {
    "nombre": "Juan",
    "apellido": "Pérez",
    "email": "juan@example.com",
    "password": "123456"
  }
  ```

### Actualizar un usuario

- **PUT** `/usuarios`
- Body (JSON):
  ```json
  {
    "id": 1,
    "nombre": "Juan",
    "apellido": "Pérez",
    "email": "juan@example.com",
    "password": "nuevoPassword"
  }
  ```

### Eliminar un usuario

- **DELETE** `/usuarios`
- Body (JSON):
  ```json
  {
    "id": 1
  }
  ```

## Ejecución

Coloca el proyecto en la carpeta `www` de WAMP/XAMPP y accede a través de tu navegador o herramientas como Postman usando la URL:

```
http://localhost/api-flight-login/
```

## Dependencias

- [Flight](https://github.com/mikecao/flight) - Micro-framework para PHP
- [firebase/php-jwt](https://github.com/firebase/php-jwt) - (Incluido, pero no usado directamente en este ejemplo)

---

Si necesitas agregar autenticación JWT o más funcionalidades, puedes extender el archivo `index.php` y aprovechar las dependencias ya incluidas.