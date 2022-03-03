<?php

namespace CalcDiff\Parsers;

use Exception;
use Symfony\Component\Yaml\Yaml;

function parse(string $rawData, string $type): array
{
    return match ($type) {
        'yml' => yamlParse($rawData),
        'yaml' => yamlParse($rawData),
        'json' => jsonParse($rawData),
        default => throw new Exception("Type not defined"),
    };
}

function jsonParse(string $rawData): array
{
    $result = json_decode($rawData, true);
    if (is_null($result)) {
        throw new Exception("Json cannot be decoded or if the encoded * data is deeper than the recursion limit.");
    }
    return $result;
}

function yamlParse(string $rawData): array
{
    return Yaml::parse($rawData);
}
