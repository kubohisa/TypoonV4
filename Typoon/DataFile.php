<?php

class DataFile
{
    public static function load($file)
    {
		if (! file_exists('../App/Data/'.$file.'.data')) return array();
		
		return unserialize(file_get_contents('../App/Data/'.$file.'.data'));
    }

    public static function save($file, $array)
    {
        file_put_contents('../App/Data/'.$file.'.data', serialize($array), LOCK_EX);
        return;
    }
}
