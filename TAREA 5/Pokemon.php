<?php 

require('partes/head.php');?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pokédex Mini</title>
    <!-- Fuente estilo Game Boy -->
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <style>
        body{
            text-align:center;
        }
        .container{
            max-width:420px; margin:60px auto; background:#fff;
            border:8px solid #EF5350; border-radius:12px; box-shadow:0 8px 20px rgba(0,0,0,.3);
            padding:25px; text-align:center;
        }
        h1{margin:0 0 20px 0; color:#EF5350; font-size:24px;}
        form{margin-bottom:25px;}
        input[type=text]{
            padding:10px; font-size:14px; border:3px solid #2A75BB; border-radius:6px 0 0 6px; width:60%;
            outline:none;
        }
        button{
            padding:10px 16px; font-size:14px; border:none; background:#FFCC00; color:#000;
            border-radius:0 6px 6px 0; cursor:pointer;
        }
        button:hover{background:#FFD633;}
        .card{
            background:#2A75BB; color:#fff; padding:20px 10px; border-radius:10px;
        }
        .card img{width:120px; image-rendering:pixelated;}
        .label{color:#FFCC00;}
        ul{list-style:none; padding:0; margin:10px 0 0 0;}
        ul li{margin:4px 0;}
        audio{margin-top:15px;}
        .error{color:#c62828; margin-top:20px;}
    </style>
    
</head>
<body>

<div class="container">
    <h1>Pokémon</h1>

    <!-- Formulario -->
    <form method="get">
        <input type="text" name="pokemon" placeholder="Ej. pikachu" required
               value="<?= isset($_GET['pokemon']) ? htmlspecialchars($_GET['pokemon']) : '' ?>">
        <button type="submit">🔍</button>
    </form>

<?php
if (isset($_GET['pokemon']) && $_GET['pokemon'] !== '') {
    $name = strtolower(trim($_GET['pokemon']));
    $url  = "https://pokeapi.co/api/v2/pokemon/" . urlencode($name);

    // --- cURL ---
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 10,
        CURLOPT_SSL_VERIFYPEER => false // cámbialo a true si tu PHP tiene CA bundle
    ]);
    $json = curl_exec($ch);
    $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($json !== false && $http === 200) {
        $data = json_decode($json, true);

        // Datos principales
        $sprite = $data['sprites']['front_default'] ?? '';
        $baseExp = $data['base_experience'] ?? '?';
        $abilitiesArr = array_map(function ($a){ return ucfirst($a['ability']['name']); }, $data['abilities']);
        $abilities = implode(', ', $abilitiesArr);

        // Sonido (cries -> latest, agregado en API en 2023). Fallback a null
        $cryUrl = $data['cries']['latest'] ?? null;
        ?>
        <div class="card">
            <?php if ($sprite): ?>
                <img src="<?= $sprite ?>" alt="<?= htmlspecialchars($name) ?>">
            <?php endif; ?>

            <p class="label">Nombre:</p>
            <p><?= ucfirst($name) ?></p>

            <p class="label">Experiencia base:</p>
            <p><?= $baseExp ?></p>

            <p class="label">Habilidades:</p>
            <ul>
                <?php foreach ($abilitiesArr as $ab): ?>
                    <li><?= $ab ?></li>
                <?php endforeach; ?>
            </ul>

            <?php if ($cryUrl): ?>
                <audio controls>
                    <source src="<?= $cryUrl ?>" type="audio/mpeg">
                    Tu navegador no soporta audio.
                </audio>
            <?php else: ?>
                <p style="margin-top:15px;">🔈 Sin cry disponible</p>
            <?php endif; ?>
        </div>
        <?php
    } else {
        echo '<p class="error">❌ Pokémon no encontrado. Intenta con otro nombre.</p>';
    }
}
?>
</div>

</body>
</html>

<?php require('partes/foot.php');?> 