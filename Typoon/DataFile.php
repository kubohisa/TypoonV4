<?php

class DataFile
{
    public static function load($file, $dir = "")
    {
        if (! file_exists('../App/Data/'.$dir.$file.'.data')) {
            return array();
        }

        $data = unserialize(file_get_contents('../App/Data/'.$dir.$file.'.data'));

        if ($data === false) {
            return array();
        }

        if (isset($data[0])) {
            $data = $data[0];
        }

        return $data;
    }

    public static function save($file, $array, $dir = "")
    {
        file_put_contents('../App/Data/'.$dir.$file.'.data', serialize($array), LOCK_EX);
        return;
    }
}
