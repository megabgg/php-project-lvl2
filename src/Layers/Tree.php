<?php

namespace CalcDiff\Layers\Tree;

use Exception;

use function CalcDiff\Layers\Node\makeNode;
use function CalcDiff\Layers\Node\makeChildNode;
use function CalcDiff\Layers\Node\getName;
use function CalcDiff\Layers\Node\getType;
use function CalcDiff\Layers\Node\getChildren;
use function Functional\flatten;
use function Functional\sort;

const ADDED = 'added';
const REMOVED = 'removed';
const NESTED = 'nested';
const UPDATED = 'updated';
const NOT_CHANGED = 'notChanged';

function makeTree(array $sourceBefore, array $sourceAfter): array
{
    $keys = array_keys(array_merge($sourceBefore, $sourceAfter));
    $sortedKeys = sort($keys, fn($a, $b) => $a <=> $b);
    return array_map(function ($key) use ($sourceBefore, $sourceAfter) {
        if (!array_key_exists($key, $sourceBefore)) {
            $node = makeNode($key, ADDED, null, $sourceAfter[$key]);
        } elseif (!array_key_exists($key, $sourceAfter)) {
            $node = makeNode($key, REMOVED, $sourceBefore[$key], null);
        } elseif (is_array($sourceBefore[$key]) && (is_array($sourceAfter[$key]))) {
            $node = makeChildNode($key, NESTED, makeTree($sourceBefore[$key], $sourceAfter[$key]));
        } elseif ($sourceBefore[$key] !== $sourceAfter[$key]) {
            $node = makeNode($key, UPDATED, $sourceBefore[$key], $sourceAfter[$key]);
        } else {
            $node = makeNode($key, NOT_CHANGED, $sourceBefore[$key], $sourceAfter[$key]);
        }
        return $node;
    }, $sortedKeys);
}

function applyFormatter(array $tree, array $formatter): string
{
    if (array_key_exists('useNodes', $formatter) === false || $formatter['useNodes'] === false) {
        $formattedElements = createFormattedElements($tree, $formatter);
        $flattenElements = flatten($formattedElements);
        return $formatter['collectString']($flattenElements);
    }

    return $formatter['collectString']($tree);
}

function createFormattedElements(array $tree, array $formatter, array $path = []): array
{
    return array_reduce($tree, function ($res, $node) use ($formatter, $path) {
        $name = getName($node);
        $newPath = array_merge($path, [$name]);
        $children = getChildren($node);
        $nested = is_array($children) ? createFormattedElements($children, $formatter, $newPath) : null;
        $element = $formatter['makeElement']($node, $nested, $newPath);
        $item = match (getType($node)) {
            ADDED => $formatter['formatAdded']($element),
            REMOVED => $formatter['formatRemoved']($element),
            NOT_CHANGED => $formatter['formatNotChanged']($element),
            UPDATED => $formatter['formatUpdated']($element),
            NESTED => $formatter['formatNested']($element),
            default => throw new Exception("Node type not defined"),
        };
        return array_merge($res, $item);
    }, []);
}
