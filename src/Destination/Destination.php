<?php
/**
 * @author Juriy Panasevich <juriy.panasevich@gmail.com>
 */

namespace JuriyPanasevich\VkWallExporter\Destination;


abstract class Destination {

    abstract function __construct(array $params = []);

    abstract public function save(array $posts): int;
}