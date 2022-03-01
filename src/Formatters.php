<?php

namespace CalcDiff\Formatters;

use Exception;

use function CalcDiff\Formatters\Stylish\getMapForStylish;
use function CalcDiff\Formatters\Plain\getMapForPlain;
use function CalcDiff\Formatters\Json\getMapForJson;

function getFormatter(string $format): array
{
    return match ($format) {
        'plain' => getMapForPlain(),
        'stylish' => getMapForStylish(),
        'json' => getMapForJson(),
        default => throw new Exception("Format not defined"),
    };
}

function boolToString(mixed $value): string
{
    return is_bool($value) ? ($value === true ? 'true' : 'false') : (is_null($value) ? 'null' : $value);
}
