<?php

namespace CalcDiff\Formatters\Stylish;

use function CalcDiff\Formatters\boolToString;
use function CalcDiff\Layers\Node\getName;
use function CalcDiff\Layers\Node\getOldValue;
use function CalcDiff\Layers\Node\getNewValue;

const SPACES = '    ';
const SEPARATOR = "\n";

function getMapForStylish()
{
    return [
        'makeElement' => function ($node, $nested, $path) {
            $level = count($path) - 1;
            return [
                'name' => getName($node),
                'oldValue' => prepareValue(getOldValue($node), $level),
                'newValue' => prepareValue(getNewValue($node), $level),
                'nested' => $nested,
                'space' => calcSpace($level)
            ];
        },
        'formatAdded' => fn($element) => formatAdded($element),
        'formatRemoved' => fn($element) => formatRemoved($element),
        'formatNotChanged' => fn($element) => formatNotChanged($element),
        'formatUpdated' => fn($element) => formatUpdated($element),
        'formatNested' => fn($element) => formatNested($element),
        'collectString' => fn($elements) => collectString($elements),
    ];
}


function formatAdded($element)
{
    return ["{$element['space']}  + {$element['name']}: {$element['newValue']}"];
}

function formatRemoved($element)
{
    return ["{$element['space']}  - {$element['name']}: {$element['oldValue']}"];
}

function formatNotChanged($element)
{
    return ["{$element['space']}    {$element['name']}: {$element['newValue']}"];
}

function formatUpdated($element)
{
    return [
        "{$element['space']}  - {$element['name']}: {$element['oldValue']}",
        "{$element['space']}  + {$element['name']}: {$element['newValue']}"
    ];
}

function formatNested($element)
{
    return ["{$element['space']}    {$element['name']}: {", $element['nested'], "{$element['space']}    }"];
}

function collectString($elements)
{
    return "{" . SEPARATOR . implode(SEPARATOR, $elements) . SEPARATOR . "}" . SEPARATOR;
}


function prepareValue($value, $level)
{
    $space = calcSpace($level);
    if (!is_array($value)) {
        return boolToString($value);
    }
    $res = implode('', array_map(function ($key, $value) use ($level, $space) {
        return SEPARATOR . "$space        $key: " . prepareValue($value, ++$level);
    }, array_keys($value), $value));

    return "{" . $res . SEPARATOR . "$space    }";
}

function calcSpace($level)
{
    return str_repeat(SPACES, $level);
}
