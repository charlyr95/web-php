// Validaciones.js - Versión sencilla
$(document).ready(function () {

    // Capturar el submit del formulario de categorías
    $('#formCategoria').on('submit', function (e) {

        const inputs = $('#formCategoria input');

        const nombreCategoria = $('#nombreCategoria').val().trim();
        const camposInvalidos = [];

        // Limpiar clases de error previas
        inputs.removeClass('is-invalid');
        inputs.removeClass('is-valid');

        // Validar campo nombre de categoría
        if (nombreCategoria === '') {
            camposInvalidos.push('El nombre de la Categoría no puede estar vacío');
            $('#nombreCategoria').addClass('is-invalid');
        }

        // Si hay campos inválidos, prevenir el envío del formulario
        if (camposInvalidos.length > 0) {
            e.preventDefault();
            alert('Error al enviar formulario:\n❌ ' + camposInvalidos.join('\n❌ ') + '\n\nCarlos Romero');
            console.log('Formulario de CATEGORÍAS no válido:', camposInvalidos);
        }

        // Prevenir el envío del formulario para pruebas pero acá se manejaría el envío real
        e.preventDefault();
    });


    // Capturar el submit del formulario de productos
    $('#formProducto').on('submit', function (e) {
        const inputs = $('#formProducto input');
        const textareas = $('#formProducto textarea');
        const select = $('#formProducto select');
        const fileInput = $('#imagen');
        const formFields = [inputs, textareas, select, fileInput];

        const nombreProducto = $('#nombre').val().trim();
        const descripcionProducto = $('#descripcion').val().trim();
        const precioProducto = $('#precio').val().trim();
        const imagenProducto = fileInput[0].files[0];
        const categoriaProducto = $('#categoria').val();
        const camposInvalidos = [];

        // Limpiar clases de error previas
        formFields.forEach(field => field.removeClass('is-invalid'));
        formFields.forEach(field => field.removeClass('is-valid'));

        // Validar campo nombre de producto
        if (nombreProducto === '') {
            camposInvalidos.push('El nombre del Producto no puede estar vacío');
            $('#nombre').addClass('is-invalid');
        }

        // Validar campo descripción de producto
        if (descripcionProducto === '') {
            camposInvalidos.push('La descripción del Producto no puede estar vacía');
            $('#descripcion').addClass('is-invalid');
        }

        // Validar campo precio de producto
        if (precioProducto === '' || isNaN(precioProducto) || parseFloat(precioProducto) <= 0) {
            camposInvalidos.push('El precio del Producto debe ser un número válido');
            $('#precio').addClass('is-invalid');
        }

        // Validar campo imagen de producto
        if (!imagenProducto) {
            camposInvalidos.push('Debes subir una imagen para el Producto');
            $('#imagen').addClass('is-invalid');
        }

        // Validar selección de categoría
        if (categoriaProducto === '') {
            camposInvalidos.push('Debes seleccionar una categoría para el Producto');
            $('#categoria').addClass('is-invalid');
        }

        // Si hay campos inválidos, prevenir el envío del formulario
        if (camposInvalidos.length > 0) {
            e.preventDefault();
            alert('Error al enviar formulario:\n❌ ' + camposInvalidos.join('\n❌ ') + '\n\nCarlos Romero');
            console.log('Formulario de PRODUCTOS no válido:', camposInvalidos);
        }

        // Prevenir el envío del formulario para pruebas pero acá se manejaría el envío real
        e.preventDefault();
    });

});
