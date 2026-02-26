<?php

namespace App;

final class Parser
{
    public function parse(string $inputPath, string $outputPath): void
    {
        $visits = [];
        $handle = fopen($inputPath, 'r');

        while (($line = fgets($handle)) !== false) {
            $commaPosition = strpos($line, ',');
            $pathStart = strpos($line, '/', 8);
            $path = substr($line, $pathStart, $commaPosition - $pathStart);
            $date = substr($line, $commaPosition + 1, 10);

            $visits[$path][$date] ??= 0;
            $visits[$path][$date]++;
        }

        fclose($handle);

        foreach ($visits as &$visitsByDate) {
            ksort($visitsByDate);
        }

        file_put_contents($outputPath, json_encode($visits, JSON_PRETTY_PRINT));
    }
}
