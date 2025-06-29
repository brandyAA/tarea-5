<?php require('partes/head.php');?>
    
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Universidades por País</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .formulario {
            margin-bottom: 20px;
        }
        .universidades {
            list-style: none;
            padding: 0;
        }
        .universidades li {
            background: #f7f7f7;
            margin-bottom: 10px;
            padding: 15px;
            border-left: 5px solid #4CAF50;
            border-radius: 6px;
        }
        .universidades a {
            color: #2196F3;
            text-decoration: none;
        }
        .universidades a:hover {
            text-decoration: underline;
        }
        .sin-resultados {
            color: #e74c3c;
        }
    </style>
</head>
<body>

    <h2>🎓 Universidades de un País</h2>

    <form class="formulario" method="GET">
        <label for="country">Nombre del país (en inglés):</label>
        <input type="text" id="country" name="country" placeholder="Dominican Republic" required>
        <button type="submit">Buscar</button>
    </form>

    <?php
    if (isset($_GET['country']) && !empty($_GET['country'])) {
        $country = htmlspecialchars($_GET['country']);
        $apiUrl  = "http://universities.hipolabs.com/search?country=" . urlencode($country);

        $response = @file_get_contents($apiUrl);

        if ($response !== false) {
            $universities = json_decode($response, true);

            if ($universities && count($universities) > 0) {
                echo "<h3>Universidades en <em>$country</em>:</h3>";
                echo "<ul class='universidades'>";

                foreach ($universities as $u) {
                    $name    = $u['name'];
                    $domains = implode(', ', $u['domains']);      // ej. uasd.edu.do
                    $web     = $u['web_pages'][0] ?? '#';         // primer enlace

                    echo "
                    <li>
                        <strong>$name</strong><br>
                        Dominio(s): $domains<br>
                        Web: <a href='$web' target='_blank'>$web</a>
                    </li>";
                }

                echo "</ul>";
            } else {
                echo "<p class='sin-resultados'>No se encontraron universidades para <strong>$country</strong>.</p>";
            }
        } else {
            echo "<p class='sin-resultados'>❌ Error al conectar con la API. Intenta de nuevo.</p>";
        }
    }
    ?>

</body>
</html>




<?php require('partes/foot.php');?>  