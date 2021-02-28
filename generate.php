#!/usr/bin/env php
<?php

$file = 'data/UnicodeData.txt';
$lines = file($file, FILE_IGNORE_NEW_LINES);

$codes = [
    0x0007, // or use \a in printf
    0x0009, // or use \t in printf
    // 0x000A, // must use \n in printf
    0x003F,
    0x007E,
    0x007F,
    0x0080,
    0x00A0,
    0x00A9,
    0x00AD,
    0x00AE,
    0x00B0,
    0x00BE,
    0x00BF,
    0x00C0,
    0x00C1,
    0x00C6,
    0x00DE,
    0x014A,
    0x0172,
    0x4000,
    0x10348,
    0x1F37A,
];

printf("#!/usr/bin/env bash\n\n");

foreach ($codes as $code) {
    $char = mb_chr($code, 'UTF-8');
    printf("# Unicode: 0x%04x / Hex: ", $code, $char);
    for ($i = 0; $i < strlen($char); $i++) {
        printf("\\x%02x", ord($char[$i]));
    }
    foreach ($lines as $line) {
        $contents = explode(';', $line);
        $id = intval($contents[0], 16);
        if ($id !== $code) {
            continue;
        }

        $isControl = ($contents[1] == '<control>');
        $label = $contents[1];
        $altLabel = $contents[10];
        printf(" / %s", (!$isControl ? $label : $altLabel) ?: 'undefined');

        break;
    }
    printf("\nUTF8_%04X=\$(printf \"", $code);
    for ($i = 0; $i < strlen($char); $i++) {
        printf("\\%03o", ord($char[$i]));
    }
    printf("\")\n");
    printf("export UTF8_%04X;\n\n", $code);
}
