<?php

namespace CalcDiff\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class CalcDiffTest extends TestCase
{
    public function addDataProvider(): array
    {
        return [
            ['before.json', 'after.json', 'stylish', 'diffStylish'],
        ];
    }

    /**
     * @dataProvider addDataProvider
     */

    public function testGenDiff(string $nameBefore, string $nameAfter, string $format, string $nameResult): void
    {
        $pathToFile1 = $this->genPath($nameBefore);
        $pathToFile2 = $this->genPath($nameAfter);
        $pathToExpected = $this->genPath($nameResult);
        $actual = genDiff($pathToFile1, $pathToFile2, $format);
        $expected = file_get_contents($pathToExpected);
        $this->assertEquals($expected, $actual);
    }

    private function genPath(string $baseName): string
    {
        $dir = 'tests/fixtures';
        return "$dir/$baseName";
    }
}
