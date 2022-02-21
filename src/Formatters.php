<?php

namespace CalcDiff\Formatters;

use function CalcDiff\Formatters\Stylish\getMapForStylish;
use function CalcDiff\Formatters\Plain\getMapForPlain;
use function CalcDiff\Formatters\Json\getMapForJson;

function getFormatter($type)
{
    switch ($type) :
        case 'plain':
            return getMapForPlain();
        case 'stylish':
            return getMapForStylish();
        case 'json':
            return getMapForJson();
    endswitch;

    return null;
}

function boolToString($value)
{
    return is_bool($value) ? ($value === true ? 'true' : 'false') : (is_null($value) ? 'null' : $value);
}
