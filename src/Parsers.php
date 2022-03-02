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
    return json_decode($rawData, true) ?: throw new Exception("Json not valid");
}

function yamlParse(string $rawData): array
{
    return Yaml::parse($rawData) ?: throw new Exception("Yaml not valid");
}
