<?php
declare(strict_types=1);
namespace Pccomponentes\Ganchudo\Util;

class Path
{
    public static function fullPath(string $workingDir, string $filePath): string
    {
        if ('/' === $filePath[0]) {
            return $filePath;
        }

        return '/' === substr($workingDir, -1) ? $workingDir . $filePath : $workingDir . '/' . $filePath;
    }
}
