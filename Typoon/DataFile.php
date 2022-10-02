<?php

class DataFile
{
    public static function load($file, $dir = "")
    {
		if (! file_exists('../App/Data/'.$dir.$file.'.data')) return array();
		
		return unserialize(file_get_contents('../App/Data/'.$dir.$file.'.data'));
    }

    public static function save($file, $array, $dir = "")
    {
        file_put_contents('../App/Data/'.$dir.$file.'.data', serialize($array), LOCK_EX);
        return;
    }
}
