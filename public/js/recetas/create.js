$(document).ready(function () {
    toastr.options = {
        positionClass: "toast-top-right",
        timeOut: 2000,
        progressBar: true,
    };
    const $addButton = $("#btn-add-product");
    const $dishId = $("#dish-id");
    const $name = $("#name");
    const $productSelect = $("#product");
    const $amountInput = $("#amount");
    const $tableBody = $("table tbody");
    const $btnSubmit = $("#btn-submit");
    let total = 0.0;

    $addButton.on("click", function () {
        const productId = $productSelect.val();
        const productName = $productSelect.find("option:selected").data("name");
        const productPrice = parseFloat(
            $productSelect.find("option:selected").data("price")
        );
        const amount = parseFloat($amountInput.val());

        if (productId && amount && amount > 0) {
            let productExists = false;

            // Check if the product already exists in the table
            $tableBody.find("tr").each(function () {
                const rowProductId = $(this).find("td").eq(0).text().trim();

                if (rowProductId == productId) {
                    // If the product exists, update the amount
                    const $row = $(this);
                    const currentAmount = parseFloat(
                        $(this).find("td").eq(3).text().trim()
                    );
                    const newAmount = currentAmount + amount;
                    $(this).find("td").eq(3).text(newAmount); // Update amount

                    // Actualizamos el precio total
                    const newTotal = newAmount * productPrice;
                    $(this).find("td").eq(4).text(newTotal.toFixed(2)); // Actualizamos el precio total
                    $row.removeClass("highlight-row");
                    setTimeout(function () {
                        $row.addClass("highlight-row");
                    }, 10);
                    productExists = true;
                    updateTotalSum();
                }
            });

            // If the product doesn't exist, create a new row
            if (!productExists) {
                const newRow = `
                    <tr class="highlight-row">
                        <td>${productId}</td>
                        <td>${productName}</td>                     
                        <td>${productPrice.toFixed(2)}</td>
                        <td>${amount}</td>
                        <td>${(productPrice * amount).toFixed(2)}</td>
                        <td><button class="btn btn-danger btn-sm delete-row">Eliminar</button></td>
                    </tr>
                `;
                $tableBody.append(newRow);
                updateTotalSum();
            }
            $productSelect.val("").trigger("change");
            $amountInput.val("");
        } else {
            toastr.warning("¡Por favor, ingrese datos validos!", "Precaución");
        }
    });

    $(document).on("click", ".delete-row", function () {
        $(this).closest("tr").remove();
        updateTotalSum();
    });

    $btnSubmit.on("click", function () {
        const products = [];
        // Iterate through each row in the table body
        $tableBody.find("tr").each(function () {
            const productId = $(this).find("td").eq(0).text().trim();
            const productName = $(this).find("td").eq(1).text().trim();
            const productPrice = parseFloat(
                $(this).find("td").eq(2).text().trim()
            );
            const amount = parseFloat($(this).find("td").eq(3).text().trim());

            products.push({
                id: productId,
                name: productName,
                price: productPrice,
                amount: amount,
            });
        });

        $.ajax({
            url: "/recetas",
            method: "POST",
            data: JSON.stringify({
                name: $name.val(),
                dish_id: $dishId.val(),
                products: products,
                total: total,
            }),
            contentType: "application/json",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.status) {
                    toastr.success(response.message, "Éxito");
                } else {
                    location.reload();
                    toastr.error(response.message, "Error");
                }
            },
            error: function (xhr, status, error) {
                // Definir un mapeo de condiciones para manejar diferentes tipos de errores
                const errorHandlers = {
                    // Manejar los errores de validación
                    422: function () {
                        let errorMessages = "";
                        $.each(
                            xhr.responseJSON.errors,
                            function (field, messages) {
                                messages.forEach(function (message) {
                                    errorMessages +=
                                        field + ": " + message + "\n";
                                });
                            }
                        );
                        toastr.error(errorMessages, "Errores de validación");
                    },
                    // Manejar los errores internos del servidor (catch)
                    500: function () {
                        toastr.error(xhr.responseJSON.message, "Error");
                    },
                    // Error general para otros casos
                    default: function () {
                        toastr.error(
                            "Hubo un error al guardar los datos. Por favor, inténtelo más tarde.",
                            "Error"
                        );
                    },
                };

                // Usar el errorHandler correspondiente según el código de estado de la respuesta
                const handler =
                    errorHandlers[xhr.status] || errorHandlers.default;
                handler();
            },
        });
    });

    function updateTotalSum() {
        total = 0;
        $("table tbody tr").each(function () {
            const rowTotal =
                parseFloat($(this).find("td").eq(4).text().trim()) || 0;
            total += rowTotal;
        });
        $("#total-sum").text(total.toFixed(2));
    }
});
