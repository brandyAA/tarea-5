<?php 

require('partes/head.php');?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Chiste Aleatorio 🤣</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #fffbe7;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .chiste-box {
            background: #fff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            max-width: 600px;
            text-align: center;
        }
        h1 {
            margin-bottom: 20px;
            color: #ff9800;
        }
        .setup {
            font-size: 20px;
            margin-bottom: 15px;
        }
        .punchline {
            font-size: 24px;
            font-weight: bold;
            color: #2e7d32;
        }
        .error {
            color: red;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="chiste-box">
    <h1>Chiste del día 🤣</h1>

<?php
$api = "https://official-joke-api.appspot.com/random_joke";

$ch = curl_init($api);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 10,
    CURLOPT_SSL_VERIFYPEER => false
]);
$respuesta = curl_exec($ch);
$http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($respuesta && $http === 200) {
    $chiste = json_decode($respuesta, true);
    echo "<div class='setup'>" . htmlspecialchars($chiste['setup']) . "</div>";
    echo "<div class='punchline'>" . htmlspecialchars($chiste['punchline']) . "</div>";
} else {
    echo "<p class='error'>❌ No se pudo obtener un chiste. Inténtalo más tarde.</p>";
}
?>
</div>

</body>
</html>



<?php require('partes/foot.php');?> 