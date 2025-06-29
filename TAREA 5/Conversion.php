<?php 

require('partes/head.php');?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Conversión de Monedas 💰</title>
<style>
    body{font-family:Arial, sans-serif;background:#f1f1f1;margin:0;padding:40px;text-align:center;}
    .card{background:#fff;border-radius:10px;display:inline-block;padding:30px 40px;box-shadow:0 4px 15px rgba(0,0,0,.1);}
    input[type="number"]{padding:10px;font-size:16px;border-radius:6px;border:2px solid #4CAF50;width:160px;outline:none;}
    button{padding:10px 15px;font-size:16px;border:none;background:#4CAF50;color:#fff;border-radius:6px;cursor:pointer;}
    button:hover{background:#43a047;}
    .resultados{margin-top:25px;text-align:left;}
    .moneda{font-size:20px;margin:8px 0;}
    .ico{margin-right:8px;}
</style>
</head>
<body>

<div class="card">
    <h2>Conversor USD ➜ DOP & más 💰</h2>
    <form method="get">
        <input type="number" name="monto" min="0" step="0.01" placeholder="Cantidad en USD"
               value="<?= isset($_GET['monto']) ? htmlspecialchars($_GET['monto']) : '' ?>" required>
        <button type="submit">Convertir</button>
    </form>

<?php
if (isset($_GET['monto']) && floatval($_GET['monto']) > 0) {
    $monto = floatval($_GET['monto']);
    $apiUrl = "https://api.exchangerate-api.com/v4/latest/USD";

    // --- cURL ---
    $ch = curl_init($apiUrl);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 10,
        CURLOPT_SSL_VERIFYPEER => false,  // cambia a true si tu PHP tiene CA bundle
    ]);
    $json = curl_exec($ch);
    $err  = curl_error($ch);
    curl_close($ch);

    if ($json !== false) {
        $data = json_decode($json, true);
        if (isset($data['rates'])) {
            // Tasas que nos interesan
            $rates = $data['rates'];
            $tasas = [
                'DOP' => ['label' => 'Pesos dominicanos', 'icon' => '🇩🇴'],
                'EUR' => ['label' => 'Euros',            'icon' => '💶'],
                'MXN' => ['label' => 'Pesos mexicanos',  'icon' => '🇲🇽'],
                'COP' => ['label' => 'Pesos colombianos','icon' => '🇨🇴'],
            ];

            echo "<div class='resultados'>";
            echo "<p><strong>\$ " . number_format($monto, 2) . " USD</strong> equivalen a:</p>";

            foreach ($tasas as $code => $info) {
                if (isset($rates[$code])) {
                    $conv = $monto * $rates[$code];
                    echo "<div class='moneda'><span class='ico'>{$info['icon']}</span>"
                       . number_format($conv, 2) . " {$info['label']} ($code)</div>";
                }
            }

            echo "<p style='margin-top:15px;font-size:12px;color:#777;'>Tasas actualizadas: "
               . htmlspecialchars($data['date']) . "</p></div>";
        } else {
            echo "<p>Error: respuesta inesperada de la API.</p>";
        }
    } else {
        echo "<p>❌ Error al conectar: $err</p>";
    }
}
?>
</div>

</body>
</html>



<?php require('partes/foot.php');?> 