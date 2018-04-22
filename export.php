<?php
/**
 * @author Juriy Panasevich <juriy.panasevich@gmail.com>
 */
require __DIR__ . '/vendor/autoload.php';

use JuriyPanasevich\VkWallExporter\Console\Console;

$params = require __DIR__ . '/src/config/params.php';
try {
    $args = getopt('g:', ['group:']);
    if (!$id = $args['g'] ?: $args['group']) {
        throw new \JuriyPanasevich\VkWallExporter\Exception\InvalidConfigurationException('Id of group is required');
    }
    $params['id'] = $id;

    $app = new \JuriyPanasevich\VkWallExporter\Application($params);
    echo Console::stringRender($app->run(), Console::FG_GREEN);
} catch (Throwable $e) {
    echo Console::stringRender($e->getMessage(), Console::FG_RED);
}
