$(document).ready(function () {
    // Evento para manejar el cambio en el campo de imagen
    $("#foto_url").on("change", function (event) {
        const file = event.target.files[0];
        const allowedTypes = [
            "image/jpeg",
            "image/jpg",
            "image/png",
            "image/webp",
        ];
        const maxSize = 2 * 1024 * 1024; // 2MB en bytes
        if (file) {
            // Validación de tipo de imagen
            if (!allowedTypes.includes(file.type)) {
                toastr.warning(
                    "¡Por favor, selecciona una imagen válida (JPG, JPEG, PNG o WEBP)!",
                    "Precaución"
                );
                $("#foto_url").val("");
                $("#imagePreview").hide();
                return;
            }
            // Validación de tamaño de la imagen
            if (file.size > maxSize) {
                toastr.error(
                    "¡La imagen no debe superar los 2MB!",
                    "Tamaño excedido"
                );
                $("#foto_url").val("");
                $("#imagePreview").hide();
                return;
            }
            // Si la imagen es válida, se puede previsualizar
            const reader = new FileReader();
            reader.onload = function (e) {
                $("#imagePreview").show();
                $("#preview").attr("src", e.target.result); // Establecer la imagen previsualizada
            };
            reader.readAsDataURL(file);
        }
    });

    // Evento para eliminar la imagen
    $("#removeImage").on("click", function () {
        $("#foto_url").val("");
        $("#imagePreview").hide();
    });
});
