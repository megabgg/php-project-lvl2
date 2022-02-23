# Files diff generator

[![Actions Status](https://github.com/megabgg/php-project-lvl2/workflows/hexlet-check/badge.svg)](https://github.com/megabgg/php-project-lvl2/actions)
<a href="https://codeclimate.com/github/megabgg/php-project-lvl2/maintainability"><img src="https://api.codeclimate.com/v1/badges/44023765a51a03b7742a/maintainability" /></a>
<a href="https://codeclimate.com/github/megabgg/php-project-lvl2/test_coverage"><img src="https://api.codeclimate.com/v1/badges/44023765a51a03b7742a/test_coverage" /></a>

Вычислитель отличий - второй проект профессии "Php разработчик" учебной платформы Хекслет. Целью данного проекта
является реализация библиотеки, с возможностью использовать ее как самостоятельной утилиты, для нахождения различий
между файлами.

**Поддерживаемые форматы:**

- yml
- json

_(форматы можно комбинировать)_

## Установка:

```bash
$ composer require megabgg/php-project-lvl2
```

## Использование как CLI приложения:

Утилита поддерживает следующие форматы генерации отчета:
<br>

### Stylish

Отображение различий в виде дерева (работает рекурсивно).

```bash
$ bin/gendiff pathToFile1 pathToFile2 --type stylish
```

Пример работы:
[![asciicast](https://asciinema.org/a/hEKvgfxaAMXzfkxJFgMBAD3O8.svg)](https://asciinema.org/a/hEKvgfxaAMXzfkxJFgMBAD3O8)


<br>

### Plain

Отображение различий в плоском формате.

```bash
$ bin/gendiff pathToFile1 pathToFile2 --type plain
```

Пример работы:
[![asciicast](https://asciinema.org/a/SpFqWztpIMb6YUuuBPWO2Gb0Y.svg)](https://asciinema.org/a/SpFqWztpIMb6YUuuBPWO2Gb0Y)
<br>

### Json

Отображение различий в формате json.

```bash
$ bin/gendiff pathToFile1 pathToFile2 --type json
```

Пример работы:
[![asciicast](https://asciinema.org/a/BSLUCBVEXpEZu2BqxCwI9ArQn.svg)](https://asciinema.org/a/BSLUCBVEXpEZu2BqxCwI9ArQn)

<br>

Если флаг _--format_ не указан, используется формат отчета по умолчанию - Plain.


## Использование как библиотеки:

```
<?php
use function CalcDiff\genDiff;
$diff = genDiff($pathToFile1, $pathToFile2);
print_r($diff);
```


