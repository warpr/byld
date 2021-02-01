<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use Webmozart\PathUtil\Path;

// from https://gist.github.com/liunian/9338301#gistcomment-1970661
function make_readable($bytes) {
    $i = floor(log($bytes, 1024));
    return round($bytes / pow(1024, $i), [0,0,2,2,3][$i]).['B','kB','MB','GB','TB'][$i];
}

function is_image($file) {
    $parts = explode(".", $file);
    $ext = array_pop($parts);
    return in_array(strtolower($ext), [ 'jpg', 'jpeg', 'png', 'gif']);
}

function display_image($path_info, $file) {
    $href = Path::join('/leaf.php', $path_info, $file);

    ?>
    <li><a href="<?= $href ?>"><img src="<?= $file ?>" /></a></li>
    <?php
}

function image_grid($dir, $path_info) {
    chdir($dir);
    $files = scandir('.');

    ?>
    <ul id="image-grid">
        <?php
            foreach ($files as $file) {
                if (is_image($file)) {
                    display_image($path_info, $file);
                }
            }
        ?>
        <li></li>
    </ul>
    <?php
}

function directory_listing($dir, $path_info) {
    chdir($dir);
    $files = scandir('.');

    ?>
    <table>
        <tr>
            <th>Filename</th>
            <th>Filesize</th>
            <th>Last Modified</th>
        </tr>
    <?php
        foreach ($files as $file) {
            if (!is_image($file)) {
                display_file($path_info, $file);
            }
        }
    ?>
    </table>
    <?php
}

function serve_file($file) {
    header('Content-Type: ' . mime_content_type($file));
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit;
}

function display_dir($path_info, $file) {
    $mtime = filemtime($file);
    $modified = date("D, j M Y G:i:s P", $mtime);
    $timestamp = date("U", $mtime);
    ?>
    <tr>
        <td><a href="<?= "$file/" ?>"><?= '[DIR] ' . $file ?></a></td>
        <td data-size="0"></td>
        <td data-time="<?= $timestamp ?>"><?= $modified ?></td>
    </tr>
    <?php
}

function display_file($path_info, $file) {
    if ($file[0] === '.' && $file != '..') {
        return;
    }

   if (is_dir($file)) {
        return display_dir($path_info, $file);
    }

    $bytes = filesize($file);
    $size = make_readable($bytes);
    $mtime = filemtime($file);
    $modified = date("D, j M Y G:i:s P", $mtime);
    $timestamp = date("U", $mtime);

    $href = Path::join('/leaf.php', $path_info, $file);

    ?>
    <tr>
        <td><a href="<?= $href ?>"><?= $file ?></a></td>
        <td data-size="<?= $bytes ?>"><?= $size ?></td>
        <td data-time="<?= $timestamp ?>"><?= $modified ?></td>
    </tr>
    <?php
}

$path_info = $_SERVER['PATH_INFO'];
$dir = Path::join(dirname(__DIR__), 'photos', $path_info);

if (is_file($dir)) {
    serve_file($dir);
} else if (is_dir($dir)) {
    // call directory_listing() below
} else {
    ?><h1>404 Not Found</h1><?php
    exit;
}

?>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
        <meta http-equiv="Accept-CH" content="Device-Memory" />
        <link rel="stylesheet" href="/nm/normalize.css/normalize.css" />
        <title>[byld] <?= $dir ?></title>
        <style>
            * {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                padding: 0;
                border: 0;
                background: black;
                color: #aaa;
            }

            @media (max-aspect-ratio: 1/1) {
                #image-grid li {
                    height: 30vh;
                }
            }

            #image-grid {
                display: flex;
                flex-wrap: wrap;
                border: 0;
                padding: 0;
                margin: 0;
            }

            #image-grid li {
                list-style: none;
                height: 20vh;
                flex-grow: 1;
                border: 0;
                padding: 0;
                margin: 0;
            }

            #image-grid img {
                max-height: 100%;
                min-width: 100%;
                object-fit: cover;
                vertical-align: bottom;
                border: 5px solid black;
                padding: 0;
                margin: 0;
            }

            #image-grid li:last-child {
                flex-grow: 10;
            }
        </style>
    </head>
    <body>
        <h1><?= $path_info ?></h1>
        <hr />
        <?php directory_listing($dir, $path_info); ?>
        <hr />
        <?php image_grid($dir, $path_info); ?>
    </body>
</html>
