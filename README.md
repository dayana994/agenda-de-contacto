# Agenda de Contactos

Aplicación web completa para gestión de contactos desarrollada con PHP, MySQL, HTML, CSS, JavaScript y Bootstrap.

## 🚀 Características

- ✅ Operaciones CRUD completas (Crear, Leer, Actualizar, Eliminar)
- ✅ **Guardado automático en archivos planos (CSV y JSON)**
- ✅ Diseño responsivo y moderno con Bootstrap 5
- ✅ Interfaz atractiva con gradientes y animaciones
- ✅ Búsqueda y filtrado de contactos
- ✅ Categorización de contactos (Personal, Trabajo, Familia, Amigos)
- ✅ Validación de formularios
- ✅ API REST para comunicación frontend-backend
- ✅ Arquitectura separada y organizada
- ✅ Visualización y descarga de archivos de contactos

## 📋 Requisitos

- PHP 7.4 o superior
- MySQL 5.7 o superior
- Servidor web (Apache/Nginx) o XAMPP/WAMP
- Navegador web moderno

## 🛠️ Instalación

### 1. Clonar o descargar el proyecto

Coloca los archivos en el directorio de tu servidor web (por ejemplo: `htdocs` en XAMPP).

### 2. Crear la base de datos

1. Abre phpMyAdmin o tu cliente MySQL preferido
2. Importa el archivo `database/schema.sql` o ejecuta el siguiente comando:

```sql
CREATE DATABASE agenda_contactos;
USE agenda_contactos;
-- Luego ejecuta el contenido de database/schema.sql
```

### 3. Configurar la conexión

Edita el archivo `config/database.php` y ajusta las credenciales según tu configuración:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'agenda_contactos');
```

### 4. Acceder a la aplicación

Abre tu navegador y visita:
```
http://localhost/ruta-del-proyecto/
```

## 📁 Estructura del Proyecto

```
agenda-contactos/
│
├── api/
│   └── contactos.php          # API REST para operaciones CRUD
│
├── config/
│   └── database.php            # Configuración de base de datos
│
├── css/
│   └── style.css               # Estilos personalizados
│
├── data/
│   ├── contactos.csv           # Archivo CSV con contactos (generado automáticamente)
│   ├── contactos.json          # Archivo JSON con contactos (generado automáticamente)
│   └── .htaccess               # Protección de archivos
│
├── database/
│   └── schema.sql              # Script SQL para crear la base de datos
│
├── includes/
│   ├── header.php              # Cabecera HTML
│   ├── footer.php              # Pie de página HTML
│   └── file_manager.php         # Gestor de archivos planos
│
├── js/
│   └── main.js                 # JavaScript principal
│
├── index.php                   # Página principal
├── ver-archivos.php            # Página para ver archivos de contactos
├── test-connection.php          # Script de prueba de conexión
└── README.md                    # Este archivo
```

## 🎨 Tecnologías Utilizadas

- **Backend**: PHP 7.4+
- **Base de Datos**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Framework CSS**: Bootstrap 5.3.2
- **Iconos**: Bootstrap Icons

## 📱 Funcionalidades

### Gestión de Contactos
- Agregar nuevos contactos con información completa
- Editar contactos existentes
- Eliminar contactos con confirmación
- Visualizar lista de contactos en cards atractivos
- **Guardado automático en archivos planos (CSV y JSON)**

### Archivos Planos
- **CSV (Comma Separated Values)**: Formato de texto plano separado por comas, fácil de abrir en Excel
- **JSON (JavaScript Object Notation)**: Formato estructurado que mantiene toda la información
- Los contactos se guardan automáticamente en ambos formatos al crear, actualizar o eliminar
- Visualización y descarga de archivos desde la interfaz web
- Ubicación: `data/contactos.csv` y `data/contactos.json`

### Búsqueda y Filtrado
- Búsqueda por nombre, apellido, email o teléfono
- Filtrado por categoría
- Búsqueda en tiempo real

### Categorías
- Personal
- Trabajo
- Familia
- Amigos

## 🔧 API Endpoints

### GET `/api/contactos.php`
Obtiene todos los contactos

### GET `/api/contactos.php?id={id}`
Obtiene un contacto específico

### POST `/api/contactos.php`
Crea un nuevo contacto

### PUT `/api/contactos.php?id={id}`
Actualiza un contacto existente

### DELETE `/api/contactos.php?id={id}`
Elimina un contacto

## 📝 Campos del Contacto

- **Nombre** (requerido)
- **Apellido** (requerido)
- **Teléfono** (requerido)
- **Email** (requerido, validado)
- **Dirección** (opcional)
- **Fecha de Nacimiento** (opcional)
- **Categoría** (Personal, Trabajo, Familia, Amigos)
- **Notas** (opcional)

## 🎯 Mejoras Futuras

- Exportar contactos a CSV/Excel
- Importar contactos desde archivo
- Fotos de perfil
- Búsqueda avanzada
- Paginación para grandes volúmenes de datos
- Autenticación de usuarios

## 👨‍💻 Desarrollo

Este proyecto fue desarrollado como parte de un proyecto académico integrador que requiere:
- Backend con PHP integrado con MySQL
- Frontend con HTML, CSS y JavaScript
- Diseño responsivo usando Bootstrap
- Operaciones CRUD completas
- Interfaz coherente y navegable

## 📄 Licencia

Este proyecto es de uso educativo.

## 👤 Autor

Desarrollado para proyecto académico integrador.

---

**Nota**: Asegúrate de tener configurado correctamente tu servidor web y base de datos antes de usar la aplicación.

