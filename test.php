<?php

require_once 'vendor/autoload.php';

use Behat\Transliterator\Transliterator;
// use Behat\Transliterator\SyncTool;

readfile('header.txt');

// printf("# SyncTool::LIB_VERSION = %s\n\n", SyncTool::LIB_VERSION);
foreach (file('vendor/behat/transliterator/src/Behat/Transliterator/SyncTool.php') as $line) 
{
    if (preg_match('/LIB_VERSION = (.*);/', $line, $matches)) {
        printf("# Behat\Transliterator\SyncTool::LIB_VERSION = %s\n\n", $matches[1]);
        break;
    }
}

$file = 'data/UnicodeData.txt';
$lines = file($file, FILE_IGNORE_NEW_LINES);

printf("default\t\t_\n\n");
printf("start\n");

foreach ($lines as $line) {
    $contents = explode(';', $line);
    // print_r($contents);
    // printf("%s\n", json_encode($contents));

    $id = intval($contents[0], 16);
    if ($id > 0xffff) {
        break;
    }

    if ($id % 0x100 == 0) {
        printf("\n#\n# Characters 0x%04X to 0x%04X\n#\n\n", $id, $id + 0xff);
    } else if ($id % 0x10 == 0) {
        printf("\n");
    }

    $char = mb_chr($id, 'UTF-8');

    $isControl = ($contents[1] == '<control>');
    $label = $contents[1];
    $altLabel = $contents[10];

    $value = $isControl ? '' : Transliterator::utf8toAscii($char);

    if ($value != '') {
        if (trim($value) == '') {
            $value = '" "';
        } else if (strstr($value, "'") !== false) {
            $value = sprintf('"%s"', $value);
        } else if (strstr($value, '"') !== false) {
            $value = sprintf("'%s'", $value);
        }
    }

    if ($value == '') {
        continue;
        // printf('# ');
    }

    if (($value == '?' || $value == '[?]') && !preg_match('/(QUESTION|INTERROBANG)/', $label . $altLabel)) {
        continue;
        // printf('# ');
    }

    printf("0x%04X\t\t%s\t# %s\n",
        $id,
        $value,
        $isControl ? $altLabel : $label
    );
}

printf("\nend\n");
