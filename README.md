## Набор функций для работы с файлами и директориями в PHP
Подробное описание функций на [Snipp.ru](http://snipp.ru/view/1).

#### Конвертирование размера файла
```php
convert_bytes($size);
```

#### Получить расширение файла
```php
ext($filename);
```

#### Получить список файлов директории в виде массива
```php
list_files($path);
```

#### Безопасное сохранение файла
```php
safe_file($filename);
```

#### Удаление каталога со всем содержимым
```php
remove_dir($dir);
```

#### Удаление содержимого каталога
```php
clear_dir($dir);
```

#### Копирование директории с ее содержимым
```php
copy_dir($src, $drc);
```