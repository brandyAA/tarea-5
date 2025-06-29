<?php require('partes/head.php');?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Clima en República Dominicana</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #e0f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            text-align:center;
        }
        .clima-box {
            background: #0288d1;
            color: white;
            padding: 30px 40px;
            border-radius: 15px;
            text-align: center;
            width: 320px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        }
        input[type="text"] {
            padding: 10px;
            width: 70%;
            border: none;
            border-radius: 5px 0 0 5px;
            outline: none;
            font-size: 16px;
        }
        button {
            padding: 10px;
            width: 25%;
            border: none;
            border-radius: 0 5px 5px 0;
            background: #01579b;
            color: white;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background: #003c8f;
        }
        .resultado {
            margin-top: 20px;
        }
        .icono {
            width: 100px;
            height: 100px;
        }
        .temp {
            font-size: 48px;
            margin: 10px 0;
        }
        .condicion {
            font-size: 22px;
        }
    </style>
</head>
<body>

<div class="clima-box">
    <form method="get">
        <input type="text" name="city" placeholder="Ciudad en RD" required
               value="<?= isset($_GET['city']) ? htmlspecialchars($_GET['city']) : 'Santo Domingo' ?>">
        <button type="submit">Buscar</button>
    </form>

    <?php
    if (isset($_GET['city']) && !empty(trim($_GET['city']))) {
        $city = trim($_GET['city']);
        $apiKey = 'http://samples.openweathermap.org/data/2.5/forecast?id=524901&appid=%3Cspan%20class='; // Tu API key aquí
        $url = "http://api.openweathermap.org/data/2.5/weather?q=" . urlencode($city) . ",DO&units=metric&lang=es&appid=" . $apiKey;

        // cURL para evitar problemas con file_get_contents
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => false, // Para evitar errores SSL locales
        ]);
        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            echo "<div class='resultado'>❌ Error en la conexión: $error</div>";
        } else {
            $data = json_decode($response, true);

            if (isset($data['main']['temp'])) {
                $temp = round($data['main']['temp']);
                $desc = ucfirst($data['weather'][0]['description']);
                $icon = $data['weather'][0]['icon'];
                $iconUrl = "https://openweathermap.org/img/wn/{$icon}@2x.png";

                // Elegir emoji según condición principal
                $condicion = $data['weather'][0]['main'];
                $emoji = "🌡️";
                if (in_array($condicion, ['Clear'])) {
                    $emoji = "☀️";
                } elseif (in_array($condicion, ['Rain', 'Drizzle', 'Thunderstorm'])) {
                    $emoji = "🌧️";
                } elseif ($condicion == 'Clouds') {
                    $emoji = "☁️";
                }

                echo "<div class='resultado'>
                        <img class='icono' src='$iconUrl' alt='Icono clima'>
                        <div class='temp'>{$temp}°C $emoji</div>
                        <div class='condicion'>$desc</div>
                        <div>Ciudad: <strong>" . htmlspecialchars($city) . "</strong></div>
                      </div>";
            } else {
                echo "<div class='resultado'>❌ No se encontró clima para <strong>" . htmlspecialchars($city) . "</strong>.</div>";
            }
        }
    }
    ?>
</div>




<?php require('partes/foot.php');?>   