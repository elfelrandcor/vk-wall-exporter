<?php
/**
 * @author Juriy Panasevich <juriy.panasevich@gmail.com>
 */

namespace JuriyPanasevich\VkWallExporter;


use ATehnix\VkClient\Exceptions\VkException;
use JuriyPanasevich\VkWallExporter\Destination\Destination;
use JuriyPanasevich\VkWallExporter\Console\Console;
use JuriyPanasevich\VkWallExporter\Exception\InvalidConfigurationException;
use JuriyPanasevich\VkWallExporter\Service\Vk;

class Application {

    protected $params = [];
    protected $id;
    protected $token;
    /** @var Destination */
    protected $destination;
    /** @var Vk */
    protected $service;

    protected $limit;
    protected $maxTries;

    /**
     * Application constructor.
     * @param array $params
     * @throws InvalidConfigurationException
     */
    public function __construct(array $params = []) {
        if (!$this->token = $params['token']) {
            throw new InvalidConfigurationException('Empty token');
        }
        $this->service = new Vk($this->token);

        $destination = $params['destination'];
        if (!class_exists($destination)) {
            throw new InvalidConfigurationException('Empty Destination classname');
        }
        $this->destination = new $destination($params['db']);

        $this->id = $params['id'];
        $this->limit = $params['limit'] ?: 100;
        $this->maxTries = $params['maxTries'] ?: 3;
    }

    /**
     * @return string
     * @throws VkException
     */
    public function run(): string {
        $count = $offset = $tries = 0;
        while (true) {
            if ($tries > $this->maxTries) {
                break;
            }
            try {
                if (!$posts = $this->service->getPostsFromWall($this->id, $offset, $this->limit)) {
                    break;
                }
                $count += $this->destination->save($posts);
                $offset += $this->limit;
                $tries = 0;
            } catch (Exception\ServiceException $e) {
                echo Console::stringRender($e->getMessage(), Console::FG_RED);
                echo Console::stringRender('Retrying after 1 sec. Current tries: ' . $tries);
                $tries++;
                sleep(1);
            }
        }

        return sprintf('Finished. Num of saved records: `%s`', $count);
    }
}