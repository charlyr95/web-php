// Scripts personalizados para el sistema de gestión
$(document).ready(function() {
    console.log('jQuery cargado correctamente');
    
    // Validación de formularios
    $('form').on('submit', function(e) {
        let isValid = true;
        
        // Validar campos requeridos
        $(this).find('input[required], textarea[required], select[required]').each(function() {
            if ($(this).val().trim() === '') {
                $(this).addClass('is-invalid');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Por favor, complete todos los campos requeridos.');
        }
    });
    
    // Limpiar validación al escribir
    $('input, textarea, select').on('input change', function() {
        $(this).removeClass('is-invalid');
    });
    
    // Confirmación para botones de cancelar
    $('button[type="reset"]').on('click', function(e) {
        if (!confirm('¿Está seguro que desea cancelar? Se perderán los datos ingresados.')) {
            e.preventDefault();
        }
    });
    
    // Efecto hover para botones
    $('.btn').hover(
        function() {
            $(this).addClass('shadow-lg');
        },
        function() {
            $(this).removeClass('shadow-lg');
        }
    );
    
    // Funciones para cargar datos dinámicamente (ejemplo)
    function cargarCategorias() {
        // Aquí iría la llamada AJAX para cargar categorías
        $.ajax({
            url: '../categorias.php',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                // Procesar datos de categorías
                console.log('Categorías cargadas:', data);
            },
            error: function() {
                console.log('Error al cargar categorías');
            }
        });
    }
    
    function cargarProductos() {
        // Aquí iría la llamada AJAX para cargar productos
        $.ajax({
            url: '../productos.php',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                // Procesar datos de productos
                console.log('Productos cargados:', data);
            },
            error: function() {
                console.log('Error al cargar productos');
            }
        });
    }
    
    // Cargar datos si estamos en las páginas de lista
    if ($('#tablaCategorias').length) {
        cargarCategorias();
    }
    
    if ($('#tablaProductos').length) {
        cargarProductos();
    }
});
