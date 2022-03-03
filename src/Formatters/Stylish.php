<?php

namespace CalcDiff\Formatters\Stylish;

use function CalcDiff\Formatters\boolToString;
use function CalcDiff\Layers\Node\getName;
use function CalcDiff\Layers\Node\getOldValue;
use function CalcDiff\Layers\Node\getNewValue;

const SPACES = '    ';
const SEPARATOR = "\n";

function getMapForStylish(): array
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


function formatAdded(array $element): array
{
    return ["{$element['space']}  + {$element['name']}: {$element['newValue']}"];
}

function formatRemoved(array $element): array
{
    return ["{$element['space']}  - {$element['name']}: {$element['oldValue']}"];
}

function formatNotChanged(array $element): array
{
    return ["{$element['space']}    {$element['name']}: {$element['newValue']}"];
}

function formatUpdated(array $element): array
{
    return [
        "{$element['space']}  - {$element['name']}: {$element['oldValue']}",
        "{$element['space']}  + {$element['name']}: {$element['newValue']}"
    ];
}

function formatNested(array $element): array
{
    return ["{$element['space']}    {$element['name']}: {", $element['nested'], "{$element['space']}    }"];
}

function collectString(array $elements): string
{
    return "{" . SEPARATOR . implode(SEPARATOR, $elements) . SEPARATOR . "}";
}


function prepareValue(mixed $value, int $level): string
{
    $space = calcSpace($level);
    if (!is_array($value)) {
        return boolToString($value);
    }
    $result = array_map(function ($key, $value) use ($level, $space) {
        $level += 1;
        $preparedValue = prepareValue($value, $level);
        return SEPARATOR . "$space        $key: " . $preparedValue;
    }, array_keys($value), $value);
    $resultString = implode('', $result);

    return "{" . $resultString . SEPARATOR . "$space    }";
}

function calcSpace(int $level): string
{
    return str_repeat(SPACES, $level);
}
