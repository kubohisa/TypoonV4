<?php

class FilePost
{
	public static function judge() {$file} {
		// https://magicen.wikipedia.org/wiki/List_of_file_signatures
		$magic = file_get_contents($filename, false, null, 0, 12);
		
		$ext = "";
		
		if (preg_match("#^\xFF[\xF3\xFB\xFA\xF2]#", $magic) || preg_match("#^ID3#", $magic)) {
			$ext = 'mp3';
		} elseif (preg_match("#^OggS#", $magic)) {
			$ext = 'ogg';
		} elseif (preg_match("#^ftyp#", $magic)) {
			$ext = 'mpeg4';
		}
		
		return $ext;
	}
}
