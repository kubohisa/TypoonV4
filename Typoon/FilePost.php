<?php

class FilePost
{
	public static function judge() {$file} {
		// https://magicen.wikipedia.org/wiki/List_of_file_signatures
		$magic = file_get_contents($filename, false, null, 0, 12);
		
		$ext = "";
		
		if (preg_match("#^\xFF[\xF3\xFB\xFA\xF2]#", $magic) || preg_match("#^ID3#", $magic)) {
			$type = 'mp3';
		} elseif (preg_match("#^OggS#", $magic)) {
			$type = 'ogg';
		} elseif (preg_match("#^ftyp#", $magic)) {
			$type = 'mpeg4';
		}
		
		return $ext;
	}
	}
}
