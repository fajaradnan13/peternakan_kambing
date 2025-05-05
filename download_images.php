<?php

$images = [
    'https://adminlte.io/themes/v3/dist/img/AdminLTELogo.png' => 'assets/img/AdminLTELogo.png',
    'https://adminlte.io/themes/v3/dist/img/user2-160x160.jpg' => 'assets/img/user2-160x160.jpg'
];

foreach ($images as $url => $path) {
    $dir = dirname($path);
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    
    $content = file_get_contents($url);
    file_put_contents($path, $content);
    echo "Downloaded: $url to $path\n";
}

echo "All images downloaded successfully!"; 