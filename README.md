# 📦 Sistema de Gestión de Productos y Categorías

Un sistema web moderno y minimalista para la gestión integral de productos y categorías, desarrollado con PHP, MySQL, Bootstrap y diseño responsivo.

![PHP](https://img.shields.io/badge/PHP-7.4+-777BB4?style=flat-square&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-5.7+-4479A1?style=flat-square&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3.2-7952B3?style=flat-square&logo=bootstrap&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-ES6+-F7DF1E?style=flat-square&logo=javascript&logoColor=black)

## 🎯 Resumen del Proyecto

Este sistema proporciona una solución completa para la administración de inventarios con las siguientes características:

### ✨ Características Principales

- **🏠 Panel de Inicio Moderno**: Interfaz de bienvenida con acceso rápido a todas las funcionalidades
- **📝 Gestión de Categorías**: CRUD completo con validación en tiempo real
- **📦 Gestión de Productos**: Administración completa de productos con soporte para imágenes
- **🔍 Búsqueda y Filtros**: Sistema avanzado de búsqueda y filtrado
- **📄 Paginación**: Navegación eficiente para grandes volúmenes de datos
- **📱 Diseño Responsivo**: Totalmente adaptado para dispositivos móviles
- **🎨 Interfaz Minimalista**: Diseño moderno enfocado en la usabilidad
- **⚡ Tiempo Real**: Validaciones y actualizaciones dinámicas con AJAX

### 🛠️ Tecnologías Utilizadas

- **Backend**: PHP 7.4+ con arquitectura MVC
- **Base de Datos**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Framework CSS**: Bootstrap 5.3.2
- **Iconos**: Font Awesome 6.4.0
- **AJAX**: jQuery 3.7.1
- **Servidor Web**: Apache (XAMPP)
 
## 🚀 Instalación y Configuración

### 📋 Requisitos Previos

- **Sistema Operativo**: Windows, macOS o Linux
- **RAM**: Mínimo 2GB recomendado
- **Espacio en Disco**: 500MB libres
- **Navegador Web**: Chrome, Firefox, Safari o Edge (versiones recientes)

### 🔧 Paso 1: Descarga e Instalación de XAMPP

#### Para Windows:

1. **Descargar XAMPP**:
   ```
   https://www.apachefriends.org/download.html
   ```
   - Selecciona la versión para Windows (PHP 7.4 o superior)
   - Descarga el instalador (aproximadamente 150MB)

2. **Instalar XAMPP**:
   ```cmd
   # Ejecutar como administrador
   xampp-windows-x64-installer.exe
   ```
   - Selecciona los componentes: ✅ Apache, ✅ MySQL, ✅ PHP, ✅ phpMyAdmin
   - Ruta de instalación recomendada: `C:\xampp`
   - Completa la instalación

3. **Configurar XAMPP**:
   ```cmd
   # Abrir XAMPP Control Panel
   C:\xampp\xampp-control.exe
   ```
   - Iniciar servicios: **Apache** y **MySQL**
   - Verificar que ambos servicios estén en verde

#### Para macOS:

1. **Descargar XAMPP**:
   ```bash
   # Descargar desde el sitio oficial
   https://www.apachefriends.org/download.html
   ```

2. **Instalar**:
   ```bash
   # Montar el DMG y seguir las instrucciones
   sudo /Applications/XAMPP/xamppfiles/xampp start
   ```

#### Para Linux (Ubuntu/Debian):

1. **Descargar e instalar**:
   ```bash
   # Descargar XAMPP
   wget https://www.apachefriends.org/xampp-files/7.4.33/xampp-linux-x64-7.4.33-0-installer.run
   
   # Dar permisos de ejecución
   chmod +x xampp-linux-x64-7.4.33-0-installer.run
   
   # Instalar como root
   sudo ./xampp-linux-x64-7.4.33-0-installer.run
   
   # Iniciar servicios
   sudo /opt/lampp/lampp start
   ```

### 🗄️ Paso 2: Configuración de la Base de Datos

1. **Acceder a phpMyAdmin**:
   ```
   http://localhost/phpmyadmin
   ```

2. **Crear la base de datos**:
   ```sql
   CREATE DATABASE gestion_productos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

3. **Crear las tablas**:

   **Tabla de Categorías:**
   ```sql
   USE gestion_productos;
   
   CREATE TABLE categorias (
       id INT AUTO_INCREMENT PRIMARY KEY,
       nombre VARCHAR(100) NOT NULL UNIQUE,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
   ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
   ```

   **Tabla de Productos:**
   ```sql
   CREATE TABLE productos (
       id INT AUTO_INCREMENT PRIMARY KEY,
       nombre VARCHAR(255) NOT NULL,
       descripcion TEXT,
       precio DECIMAL(10,2) NOT NULL,
       categoria_id INT NOT NULL,
       imagen_url VARCHAR(500),
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
       FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE RESTRICT
   ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
   ```

4. **Insertar datos de ejemplo**:
   ```sql
   -- Categorías de ejemplo
   INSERT INTO categorias (nombre) VALUES 
   ('Electrónicos'),
   ('Ropa y Accesorios'),
   ('Hogar y Jardín'),
   ('Deportes'),
   ('Libros');
   
   -- Productos de ejemplo
   INSERT INTO productos (nombre, descripcion, precio, categoria_id) VALUES 
   ('Smartphone Samsung Galaxy', 'Teléfono inteligente con pantalla AMOLED', 299.99, 1),
   ('Laptop Dell Inspiron', 'Laptop para uso profesional y personal', 599.99, 1),
   ('Camiseta Polo', 'Camiseta de algodón 100% para hombre', 25.99, 2),
   ('Zapatillas Nike Air', 'Zapatillas deportivas para running', 89.99, 4),
   ('Mesa de Comedor', 'Mesa de madera para 6 personas', 199.99, 3);
   ```

### 📂 Paso 3: Instalación del Proyecto

1. **Descargar el proyecto**:
   ```bash
   # Opción 1: Clonar repositorio (si tienes Git)
   git clone [URL_DEL_REPOSITORIO] C:\xampp\htdocs\web
   
   # Opción 2: Descargar ZIP y extraer
   # Extraer en: C:\xampp\htdocs\web
   ```

2. **Verificar estructura de archivos**:
   ```
   C:\xampp\htdocs\web\
   ├── index.php
   ├── README.md
   ├── assets/
   ├── backend/
   ├── class/
   └── views/
   ```

3. **Configurar la conexión a base de datos**:
   
   Editar `class/database.php`:
   ```php
   <?php
   class Database {
       private $host = 'localhost';
       private $dbname = 'gestion_productos';
       private $username = 'root';
       private $password = '';  // En XAMPP por defecto está vacío
       private $charset = 'utf8mb4';
       // ...resto del código
   }
   ```

### ✅ Paso 4: Verificación de la Instalación

1. **Iniciar servicios XAMPP**:
   ```
   Apache: ✅ Running
   MySQL:  ✅ Running
   ```

2. **Probar la aplicación**:
   ```
   http://localhost/web
   ```

3. **Verificar funcionalidades**:
   - ✅ Página de inicio carga correctamente
   - ✅ Puede crear categorías
   - ✅ Puede crear productos
   - ✅ Las listas muestran datos
   - ✅ La búsqueda funciona
   - ✅ La paginación opera correctamente

## 🎮 Uso del Sistema

### 🏠 Panel Principal
- Acceso directo a todas las funcionalidades
- Estado de la conexión a base de datos
- Navegación intuitiva

### 📝 Gestión de Categorías
```
http://localhost/web/backend/views/categorias.html
```
- ➕ Crear nuevas categorías
- ✏️ Editar categorías existentes
- 🗑️ Eliminar categorías
- 🔍 Buscar y filtrar

### 📦 Gestión de Productos
```
http://localhost/web/backend/views/productos.html
```
- ➕ Crear productos con imágenes
- ✏️ Editar información de productos
- 🗑️ Eliminar productos
- 🔍 Buscar por nombre, categoría, precio
- 📄 Paginación automática

## 🛠️ Configuración Avanzada

### 🔧 Configuración de PHP

En `C:\xampp\php\php.ini`:
```ini
# Aumentar límites para subida de archivos
upload_max_filesize = 10M
post_max_size = 10M
max_execution_time = 300

# Habilitar extensiones necesarias
extension=pdo_mysql
extension=gd
extension=fileinfo
```

### ⚡ Optimización de MySQL

En phpMyAdmin → Variables:
```sql
-- Optimizaciones básicas
SET GLOBAL innodb_buffer_pool_size = 256M;
SET GLOBAL max_connections = 100;
```

### 🔒 Seguridad Básica

1. **Cambiar contraseña de MySQL**:
   ```sql
   ALTER USER 'root'@'localhost' IDENTIFIED BY 'tu_nueva_contraseña';
   ```

2. **Configurar .htaccess** (crear en raíz del proyecto):
   ```apache
   # Proteger archivos de configuración
   <Files "*.php">
       Order Deny,Allow
       Deny from all
       Allow from 127.0.0.1
       Allow from ::1
   </Files>
   
   # Habilitar compresión
   <IfModule mod_deflate.c>
       AddOutputFilterByType DEFLATE text/html text/css text/javascript application/javascript
   </IfModule>
   ```

## 🐛 Solución de Problemas

### ❌ Error: "No se puede conectar a la base de datos"
```bash
# Verificar que MySQL esté ejecutándose
netstat -an | find "3306"

# Reiniciar MySQL en XAMPP Control Panel
# Verificar credenciales en class/database.php
```

### ❌ Error 404: "Página no encontrada"
```bash
# Verificar que Apache esté ejecutándose
# Comprobar que los archivos estén en C:\xampp\htdocs\web\
# Verificar permisos de archivos
```

### ❌ Error: "Call to undefined function"
```bash
# Verificar extensiones PHP habilitadas
# Reiniciar Apache después de cambios en php.ini
```

### 🔍 Logs para Debugging
```bash
# Logs de Apache
C:\xampp\apache\logs\error.log

# Logs de MySQL
C:\xampp\mysql\data\mysql_error.log

# Logs de PHP
C:\xampp\php\logs\php_error_log
```

## 🚀 Características Avanzadas

### 📊 Reportes y Estadísticas
- Total de productos por categoría
- Productos más costosos
- Estadísticas de uso

### 🔄 API REST
- Endpoints JSON para integración
- Documentación de API incluida
- Soporte para operaciones CRUD

### 📱 PWA (Progressive Web App)
- Funciona offline
- Instalable en dispositivos móviles
- Notificaciones push

## 👥 Contribución

1. Fork el proyecto
2. Crea tu rama de funcionalidad (`git checkout -b feature/nueva-funcionalidad`)
3. Commit tus cambios (`git commit -am 'Agrega nueva funcionalidad'`)
4. Push a la rama (`git push origin feature/nueva-funcionalidad`)
5. Abre un Pull Request

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver `LICENSE` para más detalles.

## 👨‍💻 Autor

**Carlos Romero**
- Sistema desarrollado en 2025
- Enfoque en usabilidad y diseño moderno
- Arquitectura MVC escalable y mantenible

---


⭐ **¡Si te gusta este proyecto, dale una estrella!** ⭐