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

### 🗄️ Paso 2: Configuración de la Base de Datos

1. **Acceder a phpMyAdmin**:
   ```
   http://localhost/phpmyadmin
   ```

2. **Crear la base de datos**:
   ```sql
   CREATE DATABASE MIPROYECTO
   ```

3. **Crear las tablas**: 

    *Nota*: Posicionarse dentro de la base de datos creada

   **Tabla de Categorías:**
   ```sql
   CREATE TABLE categorias (
      id INT AUTO_INCREMENT PRIMARY KEY,
      nombre VARCHAR(100) NOT NULL UNIQUE
   );
   ```

   **Tabla de Productos:**
   ```sql
   CREATE TABLE productos (
       id INT AUTO_INCREMENT PRIMARY KEY,
       nombre VARCHAR(255) NOT NULL,
       precio DECIMAL(10,2) NOT NULL,
       categoria_id INT NOT NULL,
       descripcion TEXT,
       imagen VARCHAR(255)
   );
   ```

### 📂 Paso 3: Instalación del Proyecto

1. **Descargar el proyecto**:
   ```bash
   # Opción 1: Clonar repositorio (si tienes Git)
   git clone https://github.com/charlyr95/web-php C:\xampp\htdocs\web
   
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
       private $dbname = 'MIPROYECTO';
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


## 👨‍💻 Autor

**Carlos Romero**
- Sistema desarrollado en 2025
- Enfoque en usabilidad y diseño moderno
- Arquitectura MVC escalable y mantenible

---


⭐ **¡Si te gusta este proyecto, dale una estrella!** ⭐