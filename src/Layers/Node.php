<?php

namespace CalcDiff\Layers\Node;

function makeNode(string $name, string $type, mixed $oldValue, mixed $newValue): array
{
    return [
        "name" => $name,
        "type" => $type,
        "oldValue" => $oldValue,
        "newValue" => $newValue
    ];
}

function makeChildNode(string $name, string $type, array $children): array
{
    return [
        "name" => $name,
        "type" => $type,
        "children" => $children
    ];
}


function getName(array $node): string
{
    return $node['name'];
}

function getType(array $node): string
{
    return $node['type'];
}

function getOldValue(array $node): mixed
{
    return $node['oldValue'] ?? null;
}

function getNewValue(array $node): mixed
{
    return $node['newValue'] ?? null;
}

function getChildren(array $node): ?array
{
    return array_key_exists('children', $node) ? $node['children'] : null;
}
