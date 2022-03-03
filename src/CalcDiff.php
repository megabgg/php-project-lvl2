<?php

namespace Differ\Differ;

use Exception;

use function CalcDiff\Parsers\parse;
use function CalcDiff\Layers\Tree\makeTree;
use function CalcDiff\Formatters\getFormatter;
use function CalcDiff\Layers\Tree\applyFormatter;

function genDiff(string $pathBefore, string $pathAfter, string $format = 'stylish'): string
{
    $rawDataBefore = getRawData($pathBefore);
    $rawDataAfter = getRawData($pathAfter);
    $tree = makeTree($rawDataBefore, $rawDataAfter);
    return formatDiff($tree, $format);
}

function formatDiff(array $tree, string $format): string
{
    $formatter = getFormatter($format);
    return applyFormatter($tree, $formatter);
}

function getRawData(string $path): array
{
    $absolutePath = realpath($path);
    if ($absolutePath === false) {
        throw new Exception("Path failure");
    }
    if (file_exists($absolutePath) === false) {
        throw new Exception("File not exists");
    }
    $fileContents = file_get_contents($absolutePath);
    if ($fileContents === false) {
        throw new Exception("Cannot access '$absolutePath' to read contents.");
    }
    $contentType = pathinfo($absolutePath, PATHINFO_EXTENSION);
    if ($contentType === '') {
        throw new Exception("File type cannot be empty");
    }
    return parse($fileContents, $contentType);
}
