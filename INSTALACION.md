# Guía de Instalación - Agenda de Contactos

## 🔧 Pasos para Conectar la Base de Datos

### Paso 1: Verificar que MySQL esté corriendo

**Si usas XAMPP:**
1. Abre el Panel de Control de XAMPP
2. Asegúrate de que el módulo **MySQL** esté iniciado (botón verde)
3. Si no está iniciado, haz clic en "Start"

**Si usas WAMP:**
1. Verifica que el icono de WAMP esté verde en la bandeja del sistema
2. Si está naranja o rojo, haz clic y selecciona "Start All Services"

**Si usas MySQL directamente:**
1. Verifica que el servicio MySQL esté corriendo en tu sistema

### Paso 2: Configurar las credenciales

Edita el archivo `config/database.php` y ajusta según tu configuración:

```php
define('DB_HOST', 'localhost');      // Normalmente 'localhost'
define('DB_USER', 'root');           // Tu usuario de MySQL
define('DB_PASS', '');               // Tu contraseña (vacía por defecto en XAMPP)
define('DB_NAME', 'agenda_contactos'); // Nombre de la base de datos
define('DB_PORT', 3306);             // Puerto de MySQL (3306 por defecto)
```

**Configuraciones comunes:**

- **XAMPP (Windows):**
  - Host: `localhost`
  - Usuario: `root`
  - Contraseña: `` (vacía)
  - Puerto: `3306`

- **WAMP (Windows):**
  - Host: `localhost`
  - Usuario: `root`
  - Contraseña: `` (vacía)
  - Puerto: `3306`

- **MAMP (Mac):**
  - Host: `localhost`
  - Usuario: `root`
  - Contraseña: `root`
  - Puerto: `8889` (o el que tengas configurado)

### Paso 3: Probar la conexión

1. Abre tu navegador
2. Ve a: `http://localhost/img/test-connection.php`
3. El script te mostrará:
   - Si la conexión al servidor funciona
   - Si la base de datos se creó correctamente
   - Si la tabla se creó correctamente
   - Cualquier error específico

### Paso 4: La aplicación creará todo automáticamente

**¡Buenas noticias!** La aplicación ahora:
- ✅ Crea la base de datos automáticamente si no existe
- ✅ Crea la tabla de contactos automáticamente si no existe
- ✅ No necesitas ejecutar scripts SQL manualmente

Solo necesitas:
1. Configurar las credenciales correctas en `config/database.php`
2. Asegurarte de que MySQL esté corriendo
3. Abrir la aplicación en el navegador

## 🐛 Solución de Problemas

### Error: "Error de conexión al servidor MySQL"

**Posibles causas:**
- MySQL no está corriendo
- Credenciales incorrectas (usuario/contraseña)
- Puerto incorrecto

**Solución:**
1. Verifica que MySQL esté iniciado en XAMPP/WAMP
2. Revisa las credenciales en `config/database.php`
3. Prueba con `test-connection.php`

### Error: "Access denied for user"

**Causa:** Usuario o contraseña incorrectos

**Solución:**
1. Abre phpMyAdmin: `http://localhost/phpmyadmin`
2. Verifica qué usuario estás usando
3. Actualiza `config/database.php` con las credenciales correctas

### Error: "Can't connect to MySQL server"

**Causa:** MySQL no está corriendo o el puerto es incorrecto

**Solución:**
1. Inicia MySQL desde XAMPP/WAMP
2. Verifica el puerto en la configuración de MySQL
3. Si usas un puerto diferente a 3306, actualiza `DB_PORT` en `config/database.php`

### La página muestra "Error de conexión" pero MySQL está corriendo

**Solución:**
1. Abre `test-connection.php` para ver el error específico
2. Verifica que el archivo `config/database.php` tenga las rutas correctas
3. Asegúrate de que PHP tenga la extensión `mysqli` habilitada

## ✅ Verificación Final

Una vez que todo funcione:
1. Abre `http://localhost/img/`
2. Deberías ver la interfaz de la agenda
3. Haz clic en "Nuevo Contacto" para probar
4. Si puedes crear un contacto, ¡todo está funcionando!

## 📞 ¿Necesitas más ayuda?

Si después de seguir estos pasos aún tienes problemas:
1. Abre `test-connection.php` y copia el mensaje de error completo
2. Verifica la versión de PHP: `http://localhost/img/test-connection.php`
3. Revisa los logs de error de PHP y MySQL

