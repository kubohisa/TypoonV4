<?php

class FilePost
{
	public static function judge() {$file} {
		// https://magicen.wikipedia.org/wiki/List_of_file_signatures
		$magic = file_get_contents($file, false, null, 0, 12);
		
		$ext = "";
		
		if (preg_match("\A\xFF[\xF3\xFB\xFA\xF2]#", $magic) || preg_match("#\AID3#", $magic)) {
			$ext = 'mp3';
		} elseif (preg_match("#\AOggS#", $magic)) {
			$ext = 'ogg';
		} elseif (preg_match("#\Aftyp#", $magic)) {
			$ext = 'mpeg4';
		}
		
		return $ext;
	}
}
