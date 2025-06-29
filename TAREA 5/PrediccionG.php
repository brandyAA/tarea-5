<?php 


require('partes/head.php');?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Predicción de Género</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .formulario {
            margin-bottom: 20px;
        }
        .resultado {
            padding: 20px;
            color: white;
            font-size: 20px;
            border-radius: 10px;
            width: fit-content;
        }
        .masculino {
            background-color: #2196F3; /* Azul 💙 */
        }
        .femenino {
            background-color: #E91E63; /* Rosa 💖 */
        }
    </style>
</head>
<body>

    <h2>🔍 Predicción de Género por Nombre</h2>

    <form class="formulario" method="GET">
        <label for="nombre">Escribe un nombre:</label>
        <input type="text" id="nombre" name="nombre" required>
        <button type="submit">Predecir</button>
    </form>

    <?php
    if (isset($_GET['nombre']) && !empty($_GET['nombre'])) {
        $nombre = htmlspecialchars($_GET['nombre']);
        $url = "https://api.genderize.io/?name=" . urlencode($nombre);

        // ⚠ Desactivamos verificación de SSL (sólo para pruebas locales)
        $contexto = stream_context_create([
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false
            ]
        ]);

        $respuesta = @file_get_contents($url, false, $contexto); // @ oculta warnings

        if ($respuesta !== false) {
            $datos = json_decode($respuesta, true);

            if (isset($datos['gender'])) {
                $genero = $datos['gender'];
                $probabilidad = $datos['probability'] * 100;

                if ($genero == "male") {
                    echo "<div class='resultado masculino'>💙 El nombre <strong>$nombre</strong> es probablemente masculino. ($probabilidad%)</div>";
                } elseif ($genero == "female") {
                    echo "<div class='resultado femenino'>💖 El nombre <strong>$nombre</strong> es probablemente femenino. ($probabilidad%)</div>";
                } else {
                    echo "<div class='resultado'>🤷 No se pudo determinar el género para <strong>$nombre</strong>.</div>";
                }
            } else {
                echo "<div class='resultado'>❌ No se pudo obtener una respuesta válida de la API.</div>";
            }
        } else {
            echo "<div class='resultado'>❌ Error al conectar con la API. Verifica tu conexión a internet.</div>";
        }
    }
    ?>

</body>
</html>



<?php require('partes/foot.php');?> 