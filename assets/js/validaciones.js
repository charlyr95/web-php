// Archivo de validaciones para formularios
// Funciones de validación personalizadas

$(document).ready(function() {
    
    // Función para validar email
    function validarEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }
    
    // Función para validar solo letras
    function validarSoloLetras(texto) {
        const regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;
        return regex.test(texto);
    }
    
    // Función para validar solo números
    function validarSoloNumeros(numero) {
        const regex = /^[0-9]+$/;
        return regex.test(numero);
    }
    
    // Función para validar precio (números con decimales)
    function validarPrecio(precio) {
        const regex = /^\d+(\.\d{1,2})?$/;
        return regex.test(precio) && parseFloat(precio) > 0;
    }
    
    // Función para validar longitud mínima
    function validarLongitudMinima(texto, minimo) {
        return texto.length >= minimo;
    }
    
    // Función para validar longitud máxima
    function validarLongitudMaxima(texto, maximo) {
        return texto.length <= maximo;
    }
    
    // Función para validar archivo de imagen
    function validarImagen(archivo) {
        const tiposPermitidos = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        const tamañoMaximo = 5 * 1024 * 1024; // 5MB
        
        if (!archivo) return false;
        
        // Validar tipo de archivo
        if (!tiposPermitidos.includes(archivo.type)) {
            return { valido: false, mensaje: 'Solo se permiten archivos JPG, PNG o GIF' };
        }
        
        // Validar tamaño
        if (archivo.size > tamañoMaximo) {
            return { valido: false, mensaje: 'El archivo no debe superar los 5MB' };
        }
        
        return { valido: true, mensaje: 'Archivo válido' };
    }
    
    // Validación en tiempo real para el nombre de categoría
    $('#nombreCategoria').on('input', function() {
        const valor = $(this).val().trim();
        const campo = $(this);
        
        // Limpiar mensajes anteriores
        campo.removeClass('is-valid is-invalid');
        campo.next('.feedback-message').remove();
        
        if (valor === '') {
            campo.addClass('is-invalid');
            campo.after('<div class="feedback-message invalid-feedback">El nombre es requerido</div>');
        } else if (!validarSoloLetras(valor)) {
            campo.addClass('is-invalid');
            campo.after('<div class="feedback-message invalid-feedback">Solo se permiten letras</div>');
        } else if (!validarLongitudMinima(valor, 2)) {
            campo.addClass('is-invalid');
            campo.after('<div class="feedback-message invalid-feedback">Mínimo 2 caracteres</div>');
        } else if (!validarLongitudMaxima(valor, 50)) {
            campo.addClass('is-invalid');
            campo.after('<div class="feedback-message invalid-feedback">Máximo 50 caracteres</div>');
        } else {
            campo.addClass('is-valid');
            campo.after('<div class="feedback-message valid-feedback">Nombre válido</div>');
        }
    });
    
    // Validación en tiempo real para el nombre de producto
    $('#nombre').on('input', function() {
        const valor = $(this).val().trim();
        const campo = $(this);
        
        // Limpiar mensajes anteriores
        campo.removeClass('is-valid is-invalid');
        campo.next('.feedback-message').remove();
        
        if (valor === '') {
            campo.addClass('is-invalid');
            campo.after('<div class="feedback-message invalid-feedback">El nombre es requerido</div>');
        } else if (!validarLongitudMinima(valor, 2)) {
            campo.addClass('is-invalid');
            campo.after('<div class="feedback-message invalid-feedback">Mínimo 2 caracteres</div>');
        } else if (!validarLongitudMaxima(valor, 100)) {
            campo.addClass('is-invalid');
            campo.after('<div class="feedback-message invalid-feedback">Máximo 100 caracteres</div>');
        } else {
            campo.addClass('is-valid');
            campo.after('<div class="feedback-message valid-feedback">Nombre válido</div>');
        }
    });
    
    // Validación en tiempo real para la descripción
    $('#descripcion').on('input', function() {
        const valor = $(this).val().trim();
        const campo = $(this);
        
        // Limpiar mensajes anteriores
        campo.removeClass('is-valid is-invalid');
        campo.next('.feedback-message').remove();
        
        if (valor === '') {
            campo.addClass('is-invalid');
            campo.after('<div class="feedback-message invalid-feedback">La descripción es requerida</div>');
        } else if (!validarLongitudMinima(valor, 10)) {
            campo.addClass('is-invalid');
            campo.after('<div class="feedback-message invalid-feedback">Mínimo 10 caracteres</div>');
        } else if (!validarLongitudMaxima(valor, 500)) {
            campo.addClass('is-invalid');
            campo.after('<div class="feedback-message invalid-feedback">Máximo 500 caracteres</div>');
        } else {
            campo.addClass('is-valid');
            campo.after('<div class="feedback-message valid-feedback">Descripción válida</div>');
        }
    });
    
    // Validación en tiempo real para el precio
    $('#precio').on('input', function() {
        const valor = $(this).val().trim();
        const campo = $(this);
        
        // Limpiar mensajes anteriores
        campo.removeClass('is-valid is-invalid');
        campo.next('.feedback-message').remove();
        
        if (valor === '') {
            campo.addClass('is-invalid');
            campo.after('<div class="feedback-message invalid-feedback">El precio es requerido</div>');
        } else if (!validarPrecio(valor)) {
            campo.addClass('is-invalid');
            campo.after('<div class="feedback-message invalid-feedback">Ingrese un precio válido (ej: 10.50)</div>');
        } else {
            campo.addClass('is-valid');
            campo.after('<div class="feedback-message valid-feedback">Precio válido</div>');
        }
    });
    
    // Validación para la imagen
    $('#imagen').on('change', function() {
        const archivo = this.files[0];
        const campo = $(this);
        
        // Limpiar mensajes anteriores
        campo.removeClass('is-valid is-invalid');
        campo.next('.feedback-message').remove();
        
        if (!archivo) {
            campo.addClass('is-invalid');
            campo.after('<div class="feedback-message invalid-feedback">Debe seleccionar una imagen</div>');
        } else {
            const validacion = validarImagen(archivo);
            if (validacion.valido) {
                campo.addClass('is-valid');
                campo.after('<div class="feedback-message valid-feedback">' + validacion.mensaje + '</div>');
            } else {
                campo.addClass('is-invalid');
                campo.after('<div class="feedback-message invalid-feedback">' + validacion.mensaje + '</div>');
            }
        }
    });
    
    // Validación para la categoría
    $('#categoria').on('change', function() {
        const valor = $(this).val();
        const campo = $(this);
        
        // Limpiar mensajes anteriores
        campo.removeClass('is-valid is-invalid');
        campo.next('.feedback-message').remove();
        
        if (valor === '' || valor === null) {
            campo.addClass('is-invalid');
            campo.after('<div class="feedback-message invalid-feedback">Debe seleccionar una categoría</div>');
        } else {
            campo.addClass('is-valid');
            campo.after('<div class="feedback-message valid-feedback">Categoría seleccionada</div>');
        }
    });
    
    // Validación completa del formulario antes del envío
    $('form').on('submit', function(e) {
        let formularioValido = true;
        const form = $(this);
        
        // Validar todos los campos requeridos
        form.find('input[required], textarea[required], select[required]').each(function() {
            const campo = $(this);
            const valor = campo.val();
            
            if (!valor || valor.trim() === '') {
                campo.addClass('is-invalid');
                formularioValido = false;
            }
        });
        
        // Validaciones específicas según el tipo de campo
        form.find('input[type="email"]').each(function() {
            if ($(this).val() && !validarEmail($(this).val())) {
                $(this).addClass('is-invalid');
                formularioValido = false;
            }
        });
        
        form.find('input[type="number"], input[step]').each(function() {
            if ($(this).val() && !validarPrecio($(this).val())) {
                $(this).addClass('is-invalid');
                formularioValido = false;
            }
        });
        
        // Si hay campos inválidos, prevenir el envío
        if (!formularioValido) {
            e.preventDefault();
            
            // Mostrar mensaje de error
            if (!form.find('.alert-danger').length) {
                form.prepend(`
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error:</strong> Por favor, corrija los errores en el formulario antes de continuar.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `);
            }
            
            // Scroll al primer campo con error
            const primerError = form.find('.is-invalid').first();
            if (primerError.length) {
                primerError.focus();
                $('html, body').animate({
                    scrollTop: primerError.offset().top - 100
                }, 500);
            }
        } else {
            // Remover mensajes de error si todo está bien
            form.find('.alert-danger').remove();
        }
    });
    
    // Función global para limpiar validaciones
    window.limpiarValidaciones = function() {
        $('.is-valid, .is-invalid').removeClass('is-valid is-invalid');
        $('.feedback-message').remove();
        $('.alert').remove();
    };
    
    // Función global para validar formulario completo
    window.validarFormularioCompleto = function(selector) {
        const form = $(selector);
        let esValido = true;
        
        form.find('input, textarea, select').each(function() {
            $(this).trigger('input change');
            if ($(this).hasClass('is-invalid')) {
                esValido = false;
            }
        });
        
        return esValido;
    };
    
    console.log('Validaciones.js cargado correctamente');
});
