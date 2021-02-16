# Detox Helper Utilities

## Automate Detox Transliteration Table Creation

The first project here is an attempt at using Sean M. Burke's [Text::Unidecode]
tables to populate [detox]'s transliteration tables, by way of Behat's
[PHP transliteration library].

## Running

### Download Unicode Table

```
cd data
wget https://www.unicode.org/Public/5.2.0/ucd/UnicodeData.txt
```

### Review

```
php -f test.php | diff -i - /path/to/detox/unicode.tbl.sample --side-by-side | less
```


[detox]: https://github.com/dharple/detox
[PHP transliteration library]: https://github.com/Behat/Transliterator
[Text::Unidecode]: https://metacpan.org/pod/Text::Unidecode
