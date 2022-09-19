<?php

class DataFile
{
    public static function load($file)
    {
        return unserialize(file_get_contents('../App/Data/'.$file.'.data'));
    }

    public static function save($file, $array)
    {
        file_put_contents('../App/Data/'.$file.'.data', serialize($array), LOCK_EX);
        return;
    }
}
