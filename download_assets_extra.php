<?php
$assets = [
    // Font Awesome webfonts
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/webfonts/fa-solid-900.woff2' => 'public/assets/plugins/fontawesome-free/webfonts/fa-solid-900.woff2',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/webfonts/fa-solid-900.woff' => 'public/assets/plugins/fontawesome-free/webfonts/fa-solid-900.woff',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/webfonts/fa-solid-900.ttf' => 'public/assets/plugins/fontawesome-free/webfonts/fa-solid-900.ttf',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/webfonts/fa-regular-400.woff2' => 'public/assets/plugins/fontawesome-free/webfonts/fa-regular-400.woff2',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/webfonts/fa-regular-400.woff' => 'public/assets/plugins/fontawesome-free/webfonts/fa-regular-400.woff',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/webfonts/fa-regular-400.ttf' => 'public/assets/plugins/fontawesome-free/webfonts/fa-regular-400.ttf',
    // Gambar logo dan user
    'https://adminlte.io/themes/v3/dist/img/AdminLTELogo.png' => 'public/assets/img/AdminLTELogo.png',
    'https://adminlte.io/themes/v3/dist/img/user2-160x160.jpg' => 'public/assets/img/user2-160x160.jpg',
    // daterangepicker (versi terbaru)
    'https://cdnjs.cloudflare.com/ajax/libs/daterangepicker/3.1/daterangepicker.min.js' => 'public/assets/plugins/daterangepicker/daterangepicker.js',
    'https://cdnjs.cloudflare.com/ajax/libs/daterangepicker/3.1/daterangepicker.min.css' => 'public/assets/plugins/daterangepicker/daterangepicker.css',
    // jquery-knob (versi terbaru)
    'https://cdnjs.cloudflare.com/ajax/libs/jquery-knob/1.2.13/jquery.knob.min.js' => 'public/assets/plugins/jquery-knob/jquery.knob.min.js',
];

foreach ($assets as $url => $path) {
    $dir = dirname($path);
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    $content = @file_get_contents($url);
    if ($content === false) {
        echo "Failed to download: $url\n";
    } else {
        file_put_contents($path, $content);
        echo "Downloaded: $url to $path\n";
    }
}
echo "All extra assets downloaded successfully!"; 