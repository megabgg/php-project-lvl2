<?php

namespace CalcDiff\Layers\Node;

function makeNode(string $name, string $type, $oldValue, $newValue)
{
    return [
        "name" => $name,
        "type" => $type,
        "oldValue" => $oldValue,
        "newValue" => $newValue
    ];
}

function makeChildNode(string $name, string $type, $children)
{
    return [
        "name" => $name,
        "type" => $type,
        "children" => $children
    ];
}


function getName($node)
{
    return $node['name'];
}

function getType($node)
{
    return $node['type'];
}

function getOldValue($node)
{
    return $node['oldValue'] ?? null;
}

function getNewValue($node)
{
    return $node['newValue'] ?? null;
}

function getChildren($node)
{
    return $node['children'] ?? null;
}
