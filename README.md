# Media Rental API - Backend

API RESTful desarrollada en **Laravel 11** para la gestion de medios publicitarios, clientes, reservas y usuarios

---

## Requisitos del Sistema

* **PHP** >= 8.2
* **Composer** >= 2.x
* **MySQL** >= 8.0 
* Verificar extension PHP `pdo_mysql`
* Verificar extension PHP `mbstring`

---

## Instalación y Configuración

1. Clonar el repositorio localmente
```bash
git clone https://github.com/johan-cabrera/media-rental-api
cd media-rental-api
```

2. Instalar dependencias
```bash
composer install
```

3. Configurar variables de entorno
Copiar el archivo de ejemplo y generar la clave de aplicación:
```bash
cp .env.example .env
php artisan key:generate
```

Editar el archivo `.env` con las credenciales de la base de datos:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=media_rental_db
DB_USERNAME=root
DB_PASSWORD=
```

4. Ejecutar migraciones y seeders
Crear las tablas y cargar los datos de prueba iniciales:
```bash
php artisan migrate --seed
```

---

### Credenciales de acceso de prueba:

Usuario Administrador: `admin@mediarental.com` 
Contraseña: `password123` 

Usuario regular: `user@mediarental.com` 
Contraseña: `password123` 

---

## Comandos para correr el proyecto

Iniciar el servidor local de Laravel:
```bash
php artisan serve
```
La API estara disponible en: `http://127.0.0.1:8000/api`

---

## Explicacion de decisiones tecnicas

* **Autenticación con Laravel Sanctum:**  
  Implemente laravel sanctum para gestionar la autenticacion mediante el uso de tokens Bearer.

* **Control de Acceso Basado en Roles:**  
  Implemente un middleware para aislar las operaciones de administracion. 

* **Descarga de CSV en Streaming:**  
  Utilice lazy cursors para el procesamiento del archivo y asi evitar desbordamiento de memoria

* **Separación de Logica usando Form Requests:**  
  Las reglas de validacion estan en clases independientes, para mantener los controladores limpios.

* **Transformacion Consistente con API Resources:**  
  Implemente los jsonResource para que las respuestas JSON esten estandarizadas y prevenir fugas de campos sensibles 
