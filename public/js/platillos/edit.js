$(document).ready(function () {
    // Validación de la imagen al seleccionar
    $("#image").on("change", function (event) {
        const file = event.target.files[0];
        const allowedTypes = [
            "image/jpeg",
            "image/jpg",
            "image/png",
            "image/webp",
        ];
        const maxSize = 2 * 1024 * 1024; // 2MB en bytes

        if (file) {
            // Verificar tipo de archivo
            if (!allowedTypes.includes(file.type)) {
                toastr.warning(
                    "¡Por favor, selecciona una imagen válida (JPG, JPEG, PNG o WEBP)!",
                    "Precaución"
                );
                $("#image").val(""); // Limpiar campo de imagen
                $("#imagePreview").hide(); // Ocultar previsualización
                return; // Terminamos la ejecución aquí
            }

            // Verificar tamaño de archivo
            if (file.size > maxSize) {
                toastr.warning(
                    "¡La imagen no debe ser mayor a 2MB!",
                    "Precaución"
                );
                $("#image").val(""); // Limpiar campo de imagen
                $("#imagePreview").hide(); // Ocultar previsualización
                return; // Terminamos la ejecución aquí
            }

            // Si la imagen es válida, previsualizamos la imagen
            var reader = new FileReader();
            reader.onload = function (e) {
                $("#imagePreview").show(); // Mostrar previsualización
                $("#preview").attr("src", e.target.result); // Establecer la imagen en el contenedor
                $("#remove_image").val("0"); // Asegurarse de que la imagen no se va a eliminar
            };
            reader.readAsDataURL(file);
        }
    });

    // Eliminar la imagen seleccionada
    $("#removeImage").on("click", function () {
        $("#image").val(""); // Limpiar campo de imagen
        $("#imagePreview").hide(); // Ocultar previsualización
        $("#remove_image").val("1"); // Marcar como eliminada
    });
});
