<?php
/**
 * @author Juriy Panasevich <juriy.panasevich@gmail.com>
 */

namespace JuriyPanasevich\VkWallExporter\Destination\Storage;


use JuriyPanasevich\VkWallExporter\Destination\Destination;
use JuriyPanasevich\VkWallExporter\Exception\DestinationException;
use PDO;

class Mysql extends Destination {

    /** @var PDO  */
    protected $pdo;
    protected $table;

    public function __construct(array $params = []) {
        $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8', $params['host'], $params['name']);
        $this->pdo = new PDO($dsn, $params['user'], $params['password']);
        $this->table = $params['table'] ?: 'exporter';
    }

    /**
     * @param array $posts
     * @return int
     * @throws DestinationException
     */
    public function save(array $posts): int {
        $sql = $this->createInsert();
        $prepared = [];
        foreach ($posts as $post) {
            $prepared[] = $this->createInsertValue($post);
        }
        $sql .= implode(',', $prepared);
        if (false === $count = $this->pdo->exec($sql)) {
            throw new DestinationException(json_encode($this->pdo->errorInfo()));
        }
        return $count;
    }

    private function createInsert() {
        $sql = 'insert ignore into %s(`groupId`, `postId`, `data`, `text`, `photo`) values';
        return sprintf($sql, $this->table);
    }

    private function createInsertValue($post) {
        $photo = $this->extractAttachment($post);
        return sprintf("('%s', '%s', '%s', '%s', '%s')", $post['owner_id'], $post['id'], json_encode($post), $post['text'], $photo);
    }

    private function extractAttachment($post): string {
        if (!$attachments = $post['attachments']) {
            return '';
        }
        $att = $attachments[0];

        if ($att['type'] === 'doc') {
            return $att['doc']['url'];
        }
        if ($att['type'] === 'photo') {
            $photo = (array)$att['photo'];
            $max = 0;
            foreach ($photo as $key => $value) {
                if (!$value) {
                    continue;
                }
                if (strpos($key, 'photo_') !== 0) {
                    continue;
                }
                $size = (int)str_replace('photo_', '', $key);
                if ($size > $max) {
                    $max = $size;
                }
            }
            if ($max) {
                return $photo['photo_' . $max];
            }
        }
        return '';
    }
}