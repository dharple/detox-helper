<?php

require_once 'vendor/autoload.php';

use Behat\Transliterator\Transliterator;

$file = 'data/UnicodeData.txt';
$lines = file($file, FILE_IGNORE_NEW_LINES);

foreach ($lines as $line) {
    $contents = explode(';', $line);
    // print_r($contents);
    // printf("%s\n", json_encode($contents));

    $id = intval($contents[0], 16);

    if ($id % 0x100 == 0) {
        printf("#\n# Characters 0x%04X to 0x%04X\n#\n\n", $id, $id + 0xff);
    } else if ($id % 0x10 == 0) {
        printf("\n");
    }

    $char = mb_chr($id, 'UTF-8');

    $isControl = ($contents[1] == '<control>');
    $label = $contents[1];
    $altLabel = $contents[10];

    if ($isControl) {
        continue;
    }

    // $value = $isControl ? '_' : Transliterator::utf8toAscii($char);
    $value = Transliterator::utf8toAscii($char);
    switch ($value) {
        case ' ':
            $value = '" "';
            break;

        case '"':
            $value = "'\"'";
            break;

        case "'":
            $value = "\"'\"";
            break;
    }

    printf("0x%04X\t\t%s\t# %s\n",
        $id,
        $value,
        $isControl ? $altLabel : $label
    );

    if ($id == 0x17F) {
        break;
    }
}
