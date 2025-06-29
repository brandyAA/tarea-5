<?php 

require('partes/head.php');?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Noticias desde WordPress 📰</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 0; padding: 20px;
            text-align:center;
        }
        .container {
            max-width: 700px;
            margin: auto;
            background: #fff;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
        }
        form {
            margin-bottom: 20px;
        }
        select {
            padding: 8px;
            font-size: 16px;
            width: 100%;
        }
        .post {
            border-bottom: 1px solid #ddd;
            padding: 15px 0;
        }
        .post h2 {
            margin: 0 0 5px 0;
            font-size: 20px;
        }
        .post p {
            margin: 0 0 10px 0;
            color: #555;
        }
        .post a {
            color: #0066cc;
            text-decoration: none;
        }
        .logo {
            max-height: 60px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Noticias desde WordPress 📰</h1>

    <form method="get">
        <label for="site">Selecciona un sitio WordPress:</label>
        <select name="site" id="site" required>
            <option value="">-- Selecciona --</option>
            <option value="https://wordpress.org/news" <?= isset($_GET['site']) && $_GET['site'] == 'https://wordpress.org/news' ? 'selected' : '' ?>>WordPress.org News</option>
            <option value="https://techcrunch.com" <?= isset($_GET['site']) && $_GET['site'] == 'https://techcrunch.com' ? 'selected' : '' ?>>TechCrunch</option>
            <option value="https://es.wordpress.org/news" <?= isset($_GET['site']) && $_GET['site'] == 'https://es.wordpress.org/news' ? 'selected' : '' ?>>WordPress Español</option>
        </select>
        <br><br>
        <button type="submit">Obtener Noticias</button>
    </form>

<?php
if (isset($_GET['site']) && !empty($_GET['site'])) {
    $site = rtrim($_GET['site'], '/');

    $apiBase = $site . "/wp-json/wp/v2";
    $logoApi = $site . "/wp-json";  // para el logo
    $postsApi = $apiBase . "/posts?per_page=3&_embed";

    // 1. Obtener LOGO (a través de site icon o name)
    $logo = '';
    $ch1 = curl_init($logoApi);
    curl_setopt_array($ch1, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => false,
    ]);
    $siteInfo = curl_exec($ch1);
    curl_close($ch1);
    $siteData = json_decode($siteInfo, true);
    if (isset($siteData['name'])) {
        $siteName = $siteData['name'];
    } else {
        $siteName = parse_url($site, PHP_URL_HOST);
    }

    if (isset($siteData['site_icon_url'])) {
        $logo = $siteData['site_icon_url'];
    }

    echo $logo ? "<img class='logo' src='$logo' alt='Logo'>" : '';
    echo "<h2>Últimas noticias de: $siteName</h2>";

    // 2. Obtener POSTS
    $ch2 = curl_init($postsApi);
    curl_setopt_array($ch2, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => false,
    ]);
    $postsData = curl_exec($ch2);
    curl_close($ch2);
    $posts = json_decode($postsData, true);

    if (is_array($posts)) {
        foreach ($posts as $post) {
            $title = $post['title']['rendered'];
            $excerpt = strip_tags($post['excerpt']['rendered']);
            $link = $post['link'];

            echo "<div class='post'>
                    <h2>$title</h2>
                    <p>$excerpt</p>
                    <a href='$link' target='_blank'>Leer más →</a>
                  </div>";
        }
    } else {
        echo "<p>❌ No se pudieron obtener las noticias.</p>";
    }
}
?>

</div>
</body>
</html>


<?php require('partes/foot.php');?> 