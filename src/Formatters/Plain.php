<?php

namespace CalcDiff\Formatters\Plain;

use function CalcDiff\Formatters\boolToString;
use function CalcDiff\Layers\Node\getOldValue;
use function CalcDiff\Layers\Node\getNewValue;

const SEPARATOR = "\n";

function getMapForPlain(): array
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

function formatAdded(array $element): array
{
    return ["Property '{$element['name']}' was added with value: {$element['newValue']}"];
}

function formatRemoved(array $element): array
{
    return ["Property '{$element['name']}' was removed"];
}

function formatNotChanged(array $element): array
{
    return [];
}

function formatUpdated(array $element): array
{
    return ["Property '{$element['name']}' was updated. From {$element['oldValue']} to {$element['newValue']}"];
}

function formatNested(array $element): array
{
    return [$element['nested']];
}

function collectString(array $elements): string
{
    return implode(SEPARATOR, $elements);
}


function prepareValue(mixed $value): string
{
    return is_array($value) ? '[complex value]' : (is_string($value) ? "'{$value}'" : boolToString($value));
}
