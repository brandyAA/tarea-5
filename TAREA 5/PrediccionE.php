<?php require('partes/head.php');?>
    
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Predicción de Edad</title>
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
            font-size: 18px;
            border-radius: 10px;
            max-width: 400px;
        }
        .categoria {
            display: flex;
            align-items: center;
            gap: 15px;
            background-color: #f0f0f0;
            border-left: 10px solid gray;
            padding: 10px;
            border-radius: 8px;
        }
        img {
            width: 80px;
            height: 80px;
            border-radius: 10px;
        }
    </style>
</head>
<body>

    <h2>🎂 Predicción de Edad por Nombre</h2>

    <form class="formulario" method="GET">
        <label for="nombre">Escribe un nombre:</label>
        <input type="text" id="nombre" name="nombre" required>
        <button type="submit">Predecir Edad</button>
    </form>

    <?php
    if (isset($_GET['nombre']) && !empty($_GET['nombre'])) {
        $nombre = htmlspecialchars($_GET['nombre']);
        $url = "https://api.agify.io/?name=" . urlencode($nombre);

        // Desactivar verificación SSL para pruebas locales
        $contexto = stream_context_create([
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false
            ]
        ]);

        $respuesta = @file_get_contents($url, false, $contexto);

        if ($respuesta !== false) {
            $datos = json_decode($respuesta, true);
            $edad = $datos['age'];

            if ($edad !== null) {
                if ($edad < 18) {
                    $emoji = "👶 Joven";
                    $imagen = "https://cdn-icons-png.flaticon.com/512/921/921166.png";
                } elseif ($edad < 60) {
                    $emoji = "🧑 Adulto";
                    $imagen = "https://cdn-icons-png.flaticon.com/512/2922/2922510.png";
                } else {
                    $emoji = "👴 Anciano";
                    $imagen = "https://cdn-icons-png.flaticon.com/512/4322/4322991.png";
                }

                echo "
                <div class='resultado'>
                    <div class='categoria'>
                        <img src='$imagen' alt='Categoría'>
                        <div>
                            <strong>$nombre</strong> tiene una edad estimada de <strong>$edad años</strong>.<br>
                            Categoría: <strong>$emoji</strong>
                        </div>
                    </div>
                </div>";
            } else {
                echo "<div class='resultado'>❌ No se pudo estimar la edad para <strong>$nombre</strong>.</div>";
            }
        } else {
            echo "<div class='resultado'>❌ Error al conectar con la API.</div>";
        }
    }
    ?>

</body>
</html>


<?php require('partes/foot.php');?> 