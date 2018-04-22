<?php
/**
 * @author Juriy Panasevich <juriy.panasevich@gmail.com>
 */

$dotenv = new Dotenv\Dotenv(dirname(__DIR__, 2));
$dotenv->load();

return [
    'db' => [
        'host' => getenv('DB_HOST'),
        'user' => getenv('DB_USER'),
        'password' => getenv('DB_PASSWORD'),
        'name' => getenv('DB_NAME'),
        'table' => getenv('DB_TABLE'),
    ],
    'token' => getenv('TOKEN'),
    'destination' => \JuriyPanasevich\VkWallExporter\Destination\Storage\Mysql::class,
];