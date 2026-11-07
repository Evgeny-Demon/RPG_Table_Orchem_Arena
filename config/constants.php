<?php

if (file_exists(__DIR__ . '/secure_bd_conf.php')) {
    require_once __DIR__ . '/secure_bd_conf.php';
}

define('MAX_FILE_SIZE', 5 * 1024 * 1024); 

define('ALLOWED_FILE_TYPES', [
    'image/jpeg',
    'image/jpg',
    'image/png'
]);

define('DIR_AVATAR', __DIR__ . '/src/compains/');

define('COMPAIN_DIR', dirname(__DIR__) . '/src/compains');
define('VIEW_COMPAIN_DIR', '/var/www/orchem-arena/src/compains/');