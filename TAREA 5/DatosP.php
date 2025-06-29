<?php 

require('partes/head.php');?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Datos de un País 🌍</title>
    <style>
        body { font-family: Arial, sans-serif; background: #e3f2fd; text-align: center; padding: 40px; }
        .card { background: white; display: inline-block; padding: 30px; border-radius: 10px; box-shadow: 0 0 15px rgba(0,0,0,.1); }
        img { width: 200px; margin-bottom: 15px; }
        .dato { font-size: 18px; margin: 8px 0; }
        .label { color: #555; font-weight: bold; }
        input, button { padding: 10px; font-size: 16px; margin-top: 10px; border-radius: 6px; border: none; }
        input { width: 250px; border: 2px solid #2196f3; }
        button { background: #2196f3; color: white; cursor: pointer; }
        button:hover { background: #1976d2; }
        .error { color: red; margin-top: 20px; }
    </style>
</head>
<body>

<div class="card">
    <h2>Datos de un País 🌍</h2>
    <form method="get">
        <input type="text" name="pais" placeholder="Ej. Dominican Republic" required value="<?= isset($_GET['pais']) ? htmlspecialchars($_GET['pais']) : '' ?>">
        <br><button type="submit">Buscar</button>
    </form>

<?php
if (isset($_GET['pais']) && $_GET['pais'] !== '') {
    $nombre = trim($_GET['pais']);
    $url = "https://restcountries.com/v3.1/name/" . urlencode($nombre);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT => 10
    ]);
    $json = curl_exec($ch);
    $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($json !== false && $http === 200) {
        $data = json_decode($json, true);
        $pais = $data[0];

        $bandera = $pais['flags']['svg'] ?? $pais['flags']['png'] ?? '';
        $nombreComun = $pais['name']['common'] ?? 'Desconocido';
        $capital = $pais['capital'][0] ?? 'Desconocida';
        $poblacion = number_format($pais['population'] ?? 0);

        // Moneda
        $moneda = 'Desconocida';
        if (isset($pais['currencies']) && is_array($pais['currencies'])) {
            foreach ($pais['currencies'] as $codigo => $info) {
                $nombreMoneda = $info['name'] ?? $codigo;
                $simbolo = $info['symbol'] ?? '';
                $moneda = "$nombreMoneda ($simbolo)";
                break; // Solo muestra una
            }
        }

        echo "<img src='$bandera' alt='Bandera de $nombreComun'>";
        echo "<div class='dato'><span class='label'>País:</span> $nombreComun</div>";
        echo "<div class='dato'><span class='label'>Capital:</span> $capital</div>";
        echo "<div class='dato'><span class='label'>Población:</span> $poblacion</div>";
        echo "<div class='dato'><span class='label'>Moneda:</span> $moneda</div>";
    } else {
        echo "<p class='error'>❌ País no encontrado o error en la API.</p>";
    }
}
?>
</div>

</body>
</html>


<?php require('partes/foot.php');?> 