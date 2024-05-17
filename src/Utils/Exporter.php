<?php

declare(strict_types=1);

namespace LodestoneUtils;

class Exporter
{
    /**
     * @param mixed $data
     *
     * @throws \JsonException
     */
    public function exportToFile(
        string $path,
        string $filename,
        $data,
    ): void {
        $file = fopen(__DIR__ . "/../../export/{$path}/{$filename}.json", 'wb');
        fwrite($file, stripcslashes(json_encode($data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE)));
        fclose($file);
    }
}
