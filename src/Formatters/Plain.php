<?php

namespace CalcDiff\Formatters\Plain;

use function CalcDiff\Formatters\boolToString;
use function CalcDiff\Layers\Node\getOldValue;
use function CalcDiff\Layers\Node\getNewValue;

const SEPARATOR = "\n";

function getMapForPlain()
{
    return [
        'makeElement' => function ($node, $nested, $path) {
            return [
                'name' => implode('.', $path),
                'oldValue' => prepareValue(getOldValue($node)),
                'newValue' => prepareValue(getNewValue($node)),
                'nested' => $nested,
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
    return ["Property '{$element['name']}' was added with value: {$element['newValue']}"];
}

function formatRemoved($element)
{
    return ["Property '{$element['name']}' was removed"];
}

function formatNotChanged($element)
{
    return [];
}

function formatUpdated($element)
{
    return ["Property '{$element['name']}' was updated. From {$element['oldValue']} to {$element['newValue']}"];
}

function formatNested($element)
{
    return [$element['nested']];
}

function collectString($elements)
{
    return implode(SEPARATOR, $elements);
}


function prepareValue($value)
{
    return is_array($value) ? '[complex value]' : (is_string($value) ? "'{$value}'" : boolToString($value));
}
