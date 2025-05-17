# Sistema de Gestión de Galerías e Imágenes

Este proyecto es una aplicación web desarrollada con Laravel que permite la gestión de galerías de imágenes con un sistema de roles (administrador/usuario). Los usuarios pueden crear galerías, subir imágenes y ordenarlas mediante arrastrar y soltar (drag-and-drop).

## Tecnologías Utilizadas

- **Laravel 10**: Framework PHP para el desarrollo backend
- **Laravel Breeze**: Implementación de autenticación
- **Tailwind CSS**: Framework CSS para el diseño responsive
- **Alpine.js**: Framework JavaScript para interactividad
- **SortableJS**: Biblioteca JavaScript para la funcionalidad de arrastrar y soltar
- **SQLite**: Base de datos ligera para el desarrollo
- **Composer**: Gestor de dependencias de PHP

## Requisitos del Sistema

- PHP 8.1 o superior
- Composer
- Node.js y npm
- SQLite (o puede configurarse con MySQL/PostgreSQL)

## Instalación

1. Clonar el repositorio:
   ```bash
   git clone [url-del-repositorio]
   cd [nombre-del-repositorio]
   ```

2. Instalar dependencias de PHP:
   ```bash
   composer install
   ```

3. Instalar dependencias de JavaScript:
   ```bash
   npm install
   ```

4. Copiar el archivo de entorno:
   ```bash
   cp .env.example .env
   ```

5. Configurar la base de datos en `.env`:
   ```
   DB_CONNECTION=sqlite
   # Eliminar o comentar las otras variables DB_*
   ```

6. Crear archivo de base de datos SQLite:
   ```bash
   touch database/database.sqlite
   ```

7. Generar la clave de la aplicación:
   ```bash
   php artisan key:generate
   ```

8. Ejecutar migraciones y seeders:
   ```bash
   php artisan migrate --seed
   ```

9. Compilar assets:
   ```bash
   npm run build
   ```

10. Crear enlace simbólico para almacenamiento:
    ```bash
    php artisan storage:link
    ```

11. Iniciar el servidor:
    ```bash
    php artisan serve
    ```

## Estructura del Proyecto

### Modelos
- **User**: Usuarios del sistema con roles
- **Role**: Roles de usuario (admin, user)
- **Gallery**: Galerías de imágenes
- **Image**: Imágenes con posición para ordenamiento

### Controladores
- **GalleryController**: Gestión de galerías
- **ImageController**: Gestión de imágenes
- **AdminController**: Panel de administración

## Funcionalidades Principales

### Sistema de Autenticación
- Registro de usuarios
- Inicio de sesión
- Recuperación de contraseña

### Gestión de Galerías
- Crear, ver, editar y eliminar galerías
- Visualizar imágenes de cada galería

### Gestión de Imágenes
- Subir imágenes a galerías
- Editar detalles de imágenes
- Eliminar imágenes
- Reordenar imágenes mediante drag-and-drop

### Panel de Administración
- Ver y gestionar todos los usuarios
- Acceder a todas las galerías
- Gestionar roles de usuario

## Sistema de Roles

### Administrador
- Acceso completo a todas las galerías e imágenes
- Gestión de usuarios
- Panel de administración

### Usuario Regular
- Gestión únicamente de sus propias galerías e imágenes
- Ordenamiento de imágenes
- Visualización personalizada

## Cuentas de Prueba

| Usuario             | Contraseña | Rol          |
|---------------------|------------|--------------|
| admin@example.com   | password   | Administrador|
| hiramwoki@example.com | password   | Usuario      |

## Implementación Técnica

### Middleware de Administración
Se implementó un middleware personalizado para restringir el acceso al panel de administración.

### Reordenamiento de Imágenes
Utilizamos SortableJS para la funcionalidad de arrastrar y soltar, con peticiones AJAX para actualizar las posiciones.

### Almacenamiento de Imágenes
Las imágenes se almacenan en el sistema de archivos utilizando el sistema de almacenamiento de Laravel.

## Buenas Prácticas Implementadas

- Separación clara de responsabilidades (MVC)
- Validación de datos en el servidor
- Protección CSRF en formularios
- Políticas de autorización
- Rutas organizadas por recursos
- Diseño responsivo

## Desarrollo y Contribución

El proyecto fue desarrollado siguiendo las mejores prácticas de Laravel, con una clara separación de responsabilidades y un enfoque en la experiencia de usuario.

---

Desarrollado como proyecto final para PI_LPBE_PHP_FINAL con Laravel.
