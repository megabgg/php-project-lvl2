<?php

namespace CalcDiff\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse(string $rawData, string $type): ?array
{
    switch ($type) :
        case 'yml':
            return yamlParse($rawData);
        case 'json':
            return jsonParse($rawData);
    endswitch;

    return null;
}

function jsonParse(string $rawData): ?array
{
    return json_decode($rawData, true) ?: null;
}

function yamlParse(string $rawData): ?array
{
    return Yaml::parse($rawData) ?: null;
}
