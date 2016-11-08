<?php
/**
* Работа с файлами и директориями.
* 
* @author http://snipp.ru/
* @version 1.0
*/
Class Files
{
	/**
	 * Конвертирование размера файла. Байты в килобайты, мегабайты.
	 * 
	 * @param int $size
	 * @return string
	 */
	public static function convertBytes($size)
	{
		$i = 0;
		while (floor($size / 1024) > 0) {
			++$i;
			$size /= 1024;
		}

		$size = str_replace('.', ',', round($size, 1));
		switch ($i) {
			case 0: return $size .= ' байт';
			case 1: return $size .= ' КБ';
			case 2: return $size .= ' МБ';
		}
	}
	
	/**
	 * Получить расширение файла.
	 * 
	 * @param string $filename
	 * @return string
	 */
	public static function ext($filename)
	{
		return mb_strtolower(mb_substr(mb_strrchr($filename, '.'), 1));
	}
	
	/**
	 * Получить список файлов директории в виде массива.
	 * То же самое делает функция scandir(), разница в том что у scandir() в массиве будут «.» и «..» и есть возможность сортировки..
	 * 
	 * @param string $path
	 * @return string
	 */
	public static function listFiles($path)
	{
		if ($path[mb_strlen($path) - 1] != '/') {
			$path .= '/';
		}

		$files = array();
		$dh = opendir($path);
		while (false !== ($file = readdir($dh))) {
			if ($file != '.' && $file != '..' && !is_dir($path.$file) && $file[0] != '.') {
				$files[] = $file;
			}
		}

		closedir($dh);
		return $files;
	}
	
	/**
	 * Безопасное сохранение файла.
	 * Если дерикторя не существует - пытается её создать.
	 * Если файл существует - к концу файла приписывает префикс.
	 * 
	 * @param string $filename
	 * @return string
	 */
	public static function safeFile($filename)
	{
		$dir = self::safeDir($filename);
		$info = pathinfo($filename);
		$name = $dir . '/' . $info['filename']; 
		$prefix = '';
		$ext = (empty($info['extension'])) ? '' : '.' . $info['extension'];

		if (is_file($name . $ext)) {
			$i = 1;
			$prefix = '_' . $i;
			while (is_file($name . $prefix . $ext)) {
				$prefix = '_' . ++$i;
			}
		}

		return $name . $prefix . $ext;
	}
	
	/**
	 * Удаление каталога со всем содержимым.
	 *
	 * @param string $dir
	 * @return void
	 */
	public static function removeDir($dir)
	{
		if ($objs = glob($dir . '/*')) {
			foreach($objs as $obj) {
				is_dir($obj) ? self::removeDir($obj) : unlink($obj);
			}
		}
		rmdir($dir);
	}
	
	/**
	 * Удаление содержимого каталога.
	 *
	 * @param string $dir
	 * @return void
	 */
	public static function clearDir($dir)
	{
		if ($objs = glob($dir . '/*')) {
			foreach($objs as $obj) {
				is_dir($obj) ? self::removeDir($obj) : unlink($obj);
			}
		}
	}
	
	/**
	 * Копирование директории с ее содержимым.
	 *
	 * @param string $src
	 * @param string $drc
	 * @return void
	 */
	public static function copyDir($src, $drc)
	{
		$dir = opendir($src);
		
		if (!is_dir($drc)) {
			mkdir($drc, 0777, true);
		}

		while (false !== ($file = readdir($dir))) {
			if ($file != '.' && $file != '..') {
				if (is_dir($src . '/' . $file)) {
					self::copyDir($src . '/' . $file, $drc . '/' . $file);
				} else {
					copy($src . '/' . $file, $drc . '/' . $file);
				}
			}
		}

		closedir($dir);
	}
}