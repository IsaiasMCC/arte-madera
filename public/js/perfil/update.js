$(document).ready(function () {
     // Configuración de toastr
     toastr.options = {
        positionClass: "toast-top-right",
        timeOut: 3000,
        progressBar: true,
    };    
    
    $("#image").on("change", function (event) {
        const file = event.target.files[0];
        const allowedTypes = [
            "image/jpeg",
            "image/jpg",
            "image/png",
            "image/webp",
        ];
        const maxSize = 2 * 1024 * 1024; // 2MB

        if (file) {
            if (!allowedTypes.includes(file.type)) {
                toastr.warning(
                    "¡Por favor, selecciona una imagen válida (JPG, JPEG, PNG o WEBP)!",
                    "Precaución"
                );
                $("#image").val("");
                return;
            }

            if (file.size > maxSize) {
                toastr.error(
                    "¡La imagen no debe superar los 2MB!",
                    "Tamaño excedido"
                );
                $("#image").val("");
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                $("#preview").attr("src", e.target.result);
            };
            reader.readAsDataURL(file);
        }
    });
});
