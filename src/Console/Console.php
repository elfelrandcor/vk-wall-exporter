<?php
/**
 * @author Juriy Panasevich <juriy.panasevich@gmail.com>
 */

namespace JuriyPanasevich\VkWallExporter\Console;

class Console {

    public const FG_BLACK  = 30;
    public const FG_RED    = 31;
    public const FG_GREEN  = 32;
    public const FG_YELLOW = 33;
    public const FG_BLUE   = 34;
    public const FG_PURPLE = 35;
    public const FG_CYAN   = 36;
    public const FG_GREY   = 37;

    public static function stringRender(string $string, int $code = self::FG_GREY): string {
        return "\033[0m" . ($code !== '' ? "\033[" . $code . 'm' : '') . $string . "\033[0m" . PHP_EOL;
    }
}