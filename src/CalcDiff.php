<?php

namespace CalcDiff;

use Exception;

use function CalcDiff\Parsers\parse;
use function CalcDiff\Layers\Tree\makeTree;
use function CalcDiff\Formatters\getFormatter;
use function CalcDiff\Layers\Tree\applyFormatter;

function genDiff(string $pathBefore, string $pathAfter, string $format = 'plain'): string
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
    if (empty($absolutePath)) {
        throw new Exception("Path not be empty");
    }
    if (!file_exists($absolutePath)) {
        throw new Exception("File not found");
    }
    $fileContents = file_get_contents($absolutePath);
    if ($fileContents === false) {
        throw new Exception("Cannot access '$absolutePath' to read contents.");
    }
    $contentType = pathinfo($absolutePath, PATHINFO_EXTENSION);
    if (empty($contentType)) {
        throw new Exception("File type cannot be empty");
    }
    return parse($fileContents, $contentType);
}
