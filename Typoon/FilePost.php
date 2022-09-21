<?php

/*
$_FILES['userfile']['name']
$_FILES['userfile']['size']
$_FILES['userfile']['tmp_name']
$_FILES['userfile']['error']

is_uploaded_file()
move_uploaded_file()

*/

class FilePost
{
	public static function judge() {$file} {
		// https://en.wikipedia.org/wiki/List_of_file_signatures
		$magic = file_get_contents($file, false, null, 0, 12);
		
		$ext = "";
		
		if (preg_match("\A\xFF[\xF3\xFB\xFA\xF2]#", $magic) || preg_match("#\AID3#", $magic)) {
			$ext = 'mp3';
		} elseif (preg_match("#\AOggS#", $magic)) {
			$ext = 'ogg';
		} elseif (preg_match("#\Aftyp#", $magic)) {
			$ext = 'm4a';
		}
		
		return $ext;
	}

	public static function getName($name) {
		return basename($_FILES[$name]['name'];
	}

	public static function checkExt() {$file, $exts} {
		$path_parts = pathinfo($file);
		$ext = $path_parts['extension'];
		
		$exts = explode("|", $exts);
		
		foreach($exts as $key => $value) {
			$value = trim($value);
			
			if ($ext === $value) return true;
		}
		
		return false;
	}

	public static function upload() {$name, $dir} {
		//
		if (! is_uploaded_file($_FILES[$name]['tmp_name'])) return false;
		if ($_FILES[$name]['error'] !== 0) return false;
		
		//
		if (move_uploaded_file($_FILES[$name]['tmp_name'], $dir.basename($_FILES[$name]['name']))) {
			return true;
		} else {
			return false;
		}
	}
}
