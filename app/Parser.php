<?php

namespace App;

use RuntimeException;

final class Parser
{
    private const URL_PATH_OFFSET = 8;
    private const DATE_LENGTH = 10;

    public function parse(string $inputPath, string $outputPath): void
    {
        $visits = [];
        $handle = fopen($inputPath, 'r');
        if ($handle === false) {
            throw new RuntimeException("Unable to open input file: {$inputPath}");
        }

        while (($line = fgets($handle)) !== false) {
            $commaPosition = strpos($line, ',');
            $pathStart = strpos($line, '/', self::URL_PATH_OFFSET);
            if ($commaPosition === false || $pathStart === false) {
                continue;
            }
            $path = substr($line, $pathStart, $commaPosition - $pathStart);
            $date = substr($line, $commaPosition + 1, self::DATE_LENGTH);

            $visits[$path][$date] ??= 0;
            $visits[$path][$date]++;
        }

        fclose($handle);

        foreach ($visits as &$visitsByDate) {
            ksort($visitsByDate);
        }

        file_put_contents($outputPath, json_encode($visits, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR));
    }
}
