<?php
/**
 * @author Juriy Panasevich <juriy.panasevich@gmail.com>
 */

namespace JuriyPanasevich\VkWallExporter\Service;


use ATehnix\VkClient\Client;
use ATehnix\VkClient\Requests\Request;
use JuriyPanasevich\VkWallExporter\Exception\ServiceException;

class Vk {

    /** @var Client */
    protected $client;

    /** @var string  */
    protected $token;

    private $lastRequestTimestamp = 0;

    public function __construct(string $token) {
        $this->client = new Client();
        $this->token = $token;
    }

    protected function getRequest($method, $params): Request {
        return new Request($method, $params, $this->token);
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \JuriyPanasevich\VkWallExporter\Exception\ServiceException
     * @throws \ATehnix\VkClient\Exceptions\VkException
     */
    protected function getResponse(Request $request) {
        if (time() - $this->lastRequestTimestamp < 1) {
            usleep(340000); // avoid limit requests per second
        }

        $res = $this->client->send($request);
        $this->lastRequestTimestamp = time();
        if (!isset($res['response'])) {
            throw new ServiceException('Error Processing Request: ' . json_encode($res));
        }
        return $res['response'];
    }

    /**
     * @param int $owner
     * @param int $offset
     * @param int $count
     * @return mixed
     * @throws \ATehnix\VkClient\Exceptions\VkException
     * @throws ServiceException
     */
    public function getPostsFromWall($owner = 0, $offset = 0, $count = 100) {
        $params = [
            'offset' => $offset,
            'count' => $count
        ];
        if (is_numeric($owner)) {
            $params['owner_id'] = $owner;
        }
        if ($owner && !is_numeric($owner)) {
            $params['domain'] = $owner;
        }

        $request = $this->getRequest('wall.get', $params);
        return $this->getResponse($request);
    }
}