<?php

namespace App\Utils;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class Utils
{
    /**
     * @throws TransportExceptionInterface
     */
    public static function ping(string $url): bool
    {
        $client = HttpClient::create();
        $response = $client->request('HEAD', $url);

        return 200 === $response->getStatusCode();
    }

    public static function cleanString(string $str): string
    {
        return \trim($str);
    }

    public static function getMemoryConsumption(): string
    {
        $memory = \memory_get_usage(true) / 1024 / 1024;
        $peakMemory = \memory_get_peak_usage(true) / 1024 / 1024;

        return 'Memory consumption '.\implode(', ', [
            'mem: '.$memory.'MB', 'peak: '.$peakMemory.'MB',
        ]);
    }

    public static function replaceMultipleSpacesByOne(string $str): string
    {
        return preg_replace('!\s+!', ' ', $str) ?? $str;
    }
}
