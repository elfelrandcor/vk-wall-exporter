<?php
/**
 * @author Juriy Panasevich <juriy.panasevich@gmail.com>
 */

namespace JuriyPanasevich\VkWallExporter;


use JuriyPanasevich\VkWallExporter\Exception\InvalidConfigurationException;

class Application {

    protected $params = [];
    protected $token;

    /**
     * Application constructor.
     * @param array $params
     * @throws InvalidConfigurationException
     */
    public function __construct(array $params = []) {
        $this->params = $params;
        if (!$this->token = $params['token']) {
            throw new InvalidConfigurationException('Empty token');
        }
    }

    public function run(): string {
        return json_encode($this->params);
    }
}