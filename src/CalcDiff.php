<?php

namespace CalcDiff;

use function CalcDiff\Parsers\parse;
use function CalcDiff\Layers\Tree\makeTree;
use function CalcDiff\Formatters\getFormatter;
use function CalcDiff\Layers\Tree\applyFormatter;

function genDiff($pathBefore, $pathAfter, $format = 'plain')
{
    $diff = createDiff($pathBefore, $pathAfter);
    return formatDiff($diff, $format);
}

function formatDiff($tree, $format)
{
    $formatter = getFormatter($format);
    return applyFormatter($tree, $formatter);
}

function createDiff($pathBefore, $pathAfter)
{
    $parsedDataBefore = getParsedData($pathBefore);
    $parsedDataAfter = getParsedData($pathAfter);
    return makeTree($parsedDataBefore, $parsedDataAfter);
}

function getParsedData($path)
{
    $fileContents = file_get_contents($path);
    $contentType = pathinfo($path, PATHINFO_EXTENSION);
    return parse($fileContents, $contentType);
}
