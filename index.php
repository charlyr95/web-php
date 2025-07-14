<?php
// Inicializar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Incluir autoloader de clases
require_once 'class/autoload.php';

// Configuración básica
$pageTitle = "Sistema de Gestión - Inicio";
$currentYear = date('Y');

// Verificar conexión a base de datos
try {
    $db = new Database();
    $connectionStatus = "Conectado a la base de datos";
    $connectionClass = "text-success";
} catch (Exception $e) {
    $connectionStatus = "Error de conexión: " . $e->getMessage();
    $connectionClass = "text-danger";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        .welcome-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .main-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
            margin: 20px;
        }
        .btn-custom {
            border-radius: 12px;
            padding: 12px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
        }
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }
        .status-indicator {
            font-size: 0.9rem;
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 8px;
            background: rgba(0, 0, 0, 0.05);
        }
        .feature-icon {
            font-size: 1.5rem;
            margin-right: 10px;
        }
    </style>
</head>
<body class="index-page">
    <div class="welcome-container">
        <div class="main-card">
            <div class="card-body p-5">
                <!-- Header -->
                <div class="text-center mb-4">
                    <i class="fas fa-boxes feature-icon text-primary"></i>
                    <h1 class="h2 mb-3">Sistema de Gestión</h1>
                    <p class="text-muted">Administración de productos y categorías</p>
                </div>

                <!-- Estado de conexión -->
                <div class="status-indicator text-center">
                    <i class="fas fa-database me-2"></i>
                    <span class="<?php echo $connectionClass; ?>">
                        <?php echo $connectionStatus; ?>
                    </span>
                </div>

                <!-- Menú principal -->
                <div class="row g-3">
                    <div class="col-md-6">
                        <a href="backend/views/lista_productos.html" class="btn btn-primary btn-custom w-100 d-flex align-items-center justify-content-center">
                            <i class="fas fa-list feature-icon"></i>
                            <span>Lista de Productos</span>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="backend/views/lista_categorias.html " class="btn btn-success btn-custom w-100 d-flex align-items-center justify-content-center">
                            <i class="fas fa-list-ul feature-icon"></i>
                            <span>Lista de Categorías</span>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="backend/views/productos.html" class="btn btn-outline-primary btn-custom w-100 d-flex align-items-center justify-content-center">
                            <i class="fas fa-box feature-icon"></i>
                            <span>Gestionar Productos</span>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="backend/views/categorias.html" class="btn btn-outline-success btn-custom w-100 d-flex align-items-center justify-content-center">
                            <i class="fas fa-tags feature-icon"></i>
                            <span>Gestionar Categorías</span>
                        </a>
                    </div>
                </div>

                <!-- Información adicional -->
                <div class="mt-4 pt-4 border-top">
                    <div class="row text-center">
                        <div class="col-4">
                            <i class="fas fa-shield-alt text-primary mb-2"></i>
                            <small class="d-block text-muted">Seguro</small>
                        </div>
                        <div class="col-4">
                            <i class="fas fa-tachometer-alt text-success mb-2"></i>
                            <small class="d-block text-muted">Rápido</small>
                        </div>
                        <div class="col-4">
                            <i class="fas fa-mobile-alt text-info mb-2"></i>
                            <small class="d-block text-muted">Responsivo</small>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="text-center mt-4 pt-3 border-top">
                    <p class="mb-0 text-muted">
                        <i class="fas fa-code me-1"></i>
                        Desarrollado por <strong>Carlos Romero</strong>
                    </p>
                    <small class="text-muted">© <?php echo $currentYear; ?> - Todos los derechos reservados</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="assets/js/scripts.js"></script>
    
    <script>
        // Animación de entrada
        document.addEventListener('DOMContentLoaded', function() {
            const card = document.querySelector('.main-card');
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.6s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100);
        });

        // Efecto hover para botones
        document.querySelectorAll('.btn-custom').forEach(btn => {
            btn.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-3px)';
            });
            
            btn.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    </script>
</body>
</html>