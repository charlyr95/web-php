<?php

/**
 * Autoloader para las clases del proyecto
 * Este archivo se encarga de cargar automáticamente las clases cuando se necesiten
 */

// Función de autoload personalizada
function autoloadClasses($className) {
    // Directorio donde están las clases
    $classDir = __DIR__ . '/';
    
    // Convertir el nombre de la clase a minúsculas para el archivo
    $classFile = $classDir . strtolower($className) . '.php';
    
    // Verificar si el archivo existe y cargarlo
    if (file_exists($classFile)) {
        require_once $classFile;
        return true;
    }
    
    // Si no se encuentra en el directorio actual, buscar en subdirectorios
    $directories = [
        __DIR__ . '/',
        __DIR__ . '/../backend/',
        __DIR__ . '/../'
    ];
    
    foreach ($directories as $dir) {
        $file = $dir . strtolower($className) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
        
        // También probar con el nombre exacto de la clase
        $file = $dir . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
    }
    
    return false;
}

// Registrar la función de autoload
spl_autoload_register('autoloadClasses');

// También podemos usar una función anónima más moderna
spl_autoload_register(function ($className) {
    $baseDir = __DIR__ . '/';
    
    // Lista de posibles ubicaciones de archivos
    $possibleFiles = [
        $baseDir . strtolower($className) . '.php',
        $baseDir . $className . '.php',
        $baseDir . str_replace('_', '/', strtolower($className)) . '.php'
    ];
    
    foreach ($possibleFiles as $file) {
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Cargar clases principales directamente para asegurar disponibilidad
$coreClasses = [
    'Database' => __DIR__ . '/database.php',
    'Productos' => __DIR__ . '/productos.php',
    'Categorias' => __DIR__ . '/categorias.php'
];

foreach ($coreClasses as $className => $filePath) {
    if (file_exists($filePath)) {
        require_once $filePath;
    }
}

// Función auxiliar para verificar si una clase está cargada
function isClassLoaded($className) {
    return class_exists($className, false);
}

// Función auxiliar para obtener todas las clases cargadas del proyecto
function getLoadedProjectClasses() {
    $allClasses = get_declared_classes();
    $projectClasses = [];
    
    // Filtrar solo las clases de nuestro proyecto
    $projectClassNames = ['Database', 'Productos', 'Categorias'];
    
    foreach ($allClasses as $class) {
        if (in_array($class, $projectClassNames)) {
            $projectClasses[] = $class;
        }
    }
    
    return $projectClasses;
}

// Manejar errores de autoload
set_error_handler(function($severity, $message, $file, $line) {
    if (strpos($message, 'Class') !== false && strpos($message, 'not found') !== false) {
        error_log("Autoload Error: $message in $file on line $line");
    }
});

?>