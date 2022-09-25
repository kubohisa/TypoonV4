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
	/*
	
	*/
	
	public static function checkExt($name, $exts) {
		$path_parts = pathinfo($_FILES[$name]['name']);
		$ext = $path_parts['extension'];
		
		if ($ext === "") return false;
		
		$exts = explode("|", $exts);
		
		
		foreach($exts as $key => $value) {
			$value = trim($value);
			if ($ext === $value) return true;
		}
		
		return false;
	}

	public static function judge($name) {
		if (! file_exists($_FILES[$name]['tmp_name'])) return "";

		// https://en.wikipedia.org/wiki/List_of_file_signatures
		$magic = file_get_contents($_FILES[$name]['tmp_name'], false, null, 0, 12);
		
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

	public static function judgeImage ($name) {
		if (! file_exists($_FILES[$name]['tmp_name'])) return false;
		
		//[php.ini]
		//extension=php_mbstring.dll
		//extension=php_exif.dll

		$type = exif_imagetype($_FILES[$name]['tmp_name']);
		if ($type === false) return false;
		
		$ext = "";
		
		switch ($type) {
			case IMAGETYPE_GIF:
				$ext = "gif";
				break;
			case IMAGETYPE_JPEG:
				$ext = "jpeg";
				break;
			case IMAGETYPE_PNG:
				$ext = "png";
				break;
			default:
				return false;
				break;
		}
		
		return $ext;
	}

	public static function podcastImage($name, $dir) {
//		echo($_FILES[$name]['tmp_name']); exit;
		
		if (! file_exists($_FILES[$name]['tmp_name'])) {
			return false;
		}
		
		$size = getimagesize($_FILES[$name]['tmp_name']);
		
		$im = null;
		switch ($size[2]) {
			case IMAGETYPE_GIF:
				$im = @imagecreatefromgif($_FILES[$name]['tmp_name']);
				break;
			case IMAGETYPE_JPEG:
				$im = @imagecreatefromjpeg($_FILES[$name]['tmp_name']);
				break;
			case IMAGETYPE_PNG:
				$im = @imagecreatefrompng($_FILES[$name]['tmp_name']);
				break;
			default:
				return false;
				break;
		}
		
		if(!$im) return false;
		
		$dst_im = imagecreatetruecolor(3000, 3000);
		
		imagecopyresampled(
			$dst_im,
			$im,
			0,
			0,
			0,
			0,
			3000,
			3000,
			$size[0],
			$size[1]
		);
		
		$dir = trim($dir, " \t\/");
		imagepng($dst_im, $dir."/podcast.png");
	}

	/*
	
	*/
	
	public static function getName($name) {
		return basename($_FILES[$name]['name']);
	}

	public static function check($name) {
		//
		if ($_FILES[$name]['error'] !== 0) return false;
		if (! file_exists($_FILES[$name]['tmp_name'])) return false;
		if (! is_uploaded_file($_FILES[$name]['tmp_name'])) return false;
		
		return true;
	}
	
	public static function upload($name, $dir) {
		//
		if ($_FILES[$name]['error'] !== 0) return false;
		if (! file_exists($_FILES[$name]['tmp_name'])) return false;
		if (! is_uploaded_file($_FILES[$name]['tmp_name'])) return false;
		
		//
		if (move_uploaded_file($_FILES[$name]['tmp_name'], $dir.basename($_FILES[$name]['name']))) {
			return true;
		} else {
			return false;
		}
	}
}
