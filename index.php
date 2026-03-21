<?php
require_once __DIR__ . '/controllers/ReservaController.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $controller = new ReservaController();

    $controller->crear();
} else {
?>


    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Reservas</title>
    </head>

    <body>
        <section>
            <p>L-V de 10 a 24, sábado de 22 a 2AM, domingo de 12 a 16</p>
            <form method="POST">
                <label for="fecha">Fecha y hora</label>
                <input
                    type="datetime-local"
                    id="meeting-time"
                    name="fecha"
                    required />

                <label for="cantidadPersonas">Cantidad de Personas</label>
                <input type="number" min="1" max="100" name="cantidadPersonas" required>
                <button type="submit">Confirmar</button>
            </form>
        </section>
    </body>

    </html>
<?php } ?>