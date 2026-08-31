<?php
declare(strict_types=1);

namespace App\Core;

final class Config
{
    private static ?array $data = null;

    public static function get(string $section): array
    {
        self::$data ??= require dirname(__DIR__, 2) . '/config/config.php';
        return self::$data[$section] ?? [];
    }

    public static function debug(): bool
    {
        return (bool) (self::get('app')['debug'] ?? false);
    }
}
