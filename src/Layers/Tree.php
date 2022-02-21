<?php

namespace CalcDiff\Layers\Tree;

use function CalcDiff\Layers\Node\makeNode;
use function CalcDiff\Layers\Node\makeChildNode;
use function CalcDiff\Layers\Node\getName;
use function CalcDiff\Layers\Node\getType;
use function CalcDiff\Layers\Node\getOldValue;
use function CalcDiff\Layers\Node\getNewValue;
use function CalcDiff\Layers\Node\getChildren;
use function Funct\Collection\flattenAll;

const ADDED = 'added';
const REMOVED = 'removed';
const NESTED = 'nested';
const UPDATED = 'updated';
const NOTCHANGED = 'notChanged';

function makeTree($sourceBefore, $sourceAfter)
{
    $keys = array_keys(array_merge($sourceBefore, $sourceAfter));
    sort($keys);
    $result = array_map(function ($key) use ($sourceBefore, $sourceAfter) {
        if (!array_key_exists($key, $sourceBefore)) {
            $node = makeNode($key, ADDED, null, $sourceAfter[$key]);
        } elseif (!array_key_exists($key, $sourceAfter)) {
            $node = makeNode($key, REMOVED, $sourceBefore[$key], null);
        } elseif (is_array($sourceBefore[$key]) && (is_array($sourceAfter[$key]))) {
            $node = makeChildNode($key, NESTED, makeTree($sourceBefore[$key], $sourceAfter[$key]));
        } elseif ($sourceBefore[$key] !== $sourceAfter[$key]) {
            $node = makeNode($key, UPDATED, $sourceBefore[$key], $sourceAfter[$key]);
        } else {
            $node = makeNode($key, NOTCHANGED, $sourceBefore[$key], $sourceAfter[$key]);
        }
        return $node;
    }, $keys);

    return $result;
}

function applyFormatter($tree, $formatter)
{
    if (empty($formatter['useNodes']) || !$formatter['useNodes']) {
        $formattedElements = createFormattedElements($tree, $formatter);
        $flattenElements = flattenAll($formattedElements);
        return $formatter['collectString']($flattenElements);
    }

    return $formatter['collectString']($tree);
}

function createFormattedElements($tree, $formatter, $path = [])
{
    return array_reduce($tree, function ($res, $node) use ($formatter, $path) {
        $path[] = getName($node);
        $nested = ($children = getChildren($node)) ? createFormattedElements($children, $formatter, $path) : null;
        $element = $formatter['makeElement']($node, $nested, $path);

        switch (getType($node)) {
            case ADDED:
                $item = $formatter['formatAdded']($element);
                break;
            case REMOVED:
                $item = $formatter['formatRemoved']($element);
                break;
            case NOTCHANGED:
                $item = $formatter['formatNotChanged']($element);
                break;
            case UPDATED:
                $item = $formatter['formatUpdated']($element);
                break;
            case NESTED:
                $item = $formatter['formatNested']($element);
                break;
        }

        return !empty($item) ? array_merge($res, $item) : $res;
    }, []);
}
