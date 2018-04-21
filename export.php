<?php
/**
 * @author Juriy Panasevich <juriy.panasevich@gmail.com>
 */
require __DIR__ . '/vendor/autoload.php';

use JuriyPanasevich\VkWallExporter\Console\Console;

$params = require __DIR__ . '/src/config/params.php';
try {
    $app = new \JuriyPanasevich\VkWallExporter\Application($params);
    echo Console::stringRender($app->run(), Console::FG_GREEN);
} catch (\Exception $e) {
    echo Console::stringRender($e->getMessage(), Console::FG_RED);
}
