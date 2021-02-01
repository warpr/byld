<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use Webmozart\PathUtil\Path;

$path_info = $_SERVER['PATH_INFO'];
$parent_dir = Path::join('/admin.php', $path_info, '/../') . '/';
$image_file = Path::join('/admin.php', $path_info);
$on_disk = Path::join(dirname(__DIR__), 'photos', $path_info);

?>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
        <meta http-equiv="Accept-CH" content="Device-Memory" />
        <link rel="stylesheet" href="/nm/normalize.css/normalize.css" />
        <title>[byld] <?= $path_info ?></title>
    </head>
    <body>
        <h1><?= $path_info ?></h1>
        <a href="<?= $parent_dir ?>">&lt;-- back</a><br /><br />
        <img src="<?= $image_file ?>" style="max-width: 100vw; max-height: 80vh;" />
        <pre>
            <?php print_r(exif_read_data($on_disk)); ?>
        </pre>
    </body>
</html>

