<?php

namespace Bridging\Bpjs\Support;

class Timeout
{
    public const SECONDS = 60;

    public static function options(array $options): array
    {
        $requested = (float) ($options['timeout'] ?? self::SECONDS);
        $options['timeout'] = $requested > 0 ? min($requested, self::SECONDS) : self::SECONDS;

        return $options;
    }

    public static function error(\Throwable $exception): string
    {
        if ((method_exists($exception, 'getResponse') && $exception->getResponse() !== null)
            || !preg_match('/cURL error 28|(?:operation|connection) timed out|timed out after/i', $exception->getMessage())) {
            return $exception->getMessage();
        }

        return json_encode([
            'metaData' => [
                'code' => 504,
                'message' => 'Server BPJS tidak merespons dalam 60 detik. Silakan coba kembali dalam beberapa menit.',
            ],
            'response' => null,
        ]);
    }
}
