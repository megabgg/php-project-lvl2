<?php

namespace CalcDiff\Formatters\Json;

function getMapForJson(): array
{
    return [
        'useNodes' => true,
        'collectString' => function ($elements) {
            return json_encode($elements, JSON_PRETTY_PRINT);
        },
    ];
}
