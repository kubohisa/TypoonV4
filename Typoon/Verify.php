<?php

class Verify
{
    /*

    */
    public static $error;
    public static $flag;
    public static $required;

    private $value;

    /*

    */
    public static function errorSet(string $key)
    {
        self::$error[$key] = true;

        self::$flag = true;
    }

    /*

    */
    public static function set(string &$data): Verify
    {
        return new Verify($data);
    }

    private function __construct(string &$data)
    {
        //
        self::$error = array();

        self::$flag = false;

        self::$required = false;

        //
        $data = mb_convert_encoding($data, "UTF-8", "auto");
        $this->value = &$data;
    }

    public function result()
    {
        //
        self::$error['paramData'] = $this->value;

        //
        if (self::$required === true) {
            self::$error['errorFlag'] = self::$flag;
        } elseif ($this->value !== "") {
            self::$error['errorFlag'] = self::$flag;
        } else {
            self::$error['errorFlag'] = false;
        }

        //
        return self::$error;
    }

    /*

    */

    public function trim()
    {
        $this->value = preg_replace('#\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z#u', '', $this->value);

        return $this;
    }

    public function plain()
    {
        $this->value = preg_replace("#(\r\n)+#", "\r\n\r\n", $this->value);
        $this->value = preg_replace('#\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z#u', '', $this->value);

        $array = explode("\r\n", $this->value);
        foreach ($array as $key => $value) {
            $array[$key] = preg_replace('#\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z#u', '', $value);
        }
        $this->value = implode("\r\n", $array);

        return $this;
    }

    public function japaneseText()
    {
        $this->value = preg_replace("#(\r\n)+#", "\r\n\r\n", $this->value);
        $this->value = preg_replace('#\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z#u', '', $this->value);

        $array = explode("\r\n", $this->value);
        foreach ($array as $key => $value) {
            $array[$key] = preg_replace('#\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z#u', '', $value);
            if ($array[$key] !== "") {
                $array[$key] = "　".$array[$key];
            }
        }
        $this->value = implode("\r\n", $array);

        return $this;
    }

    /*

    */

    public function htmlEscape()
    {
        $this->value = htmlentities($this->value, ENT_QUOTES);

        return $this;
    }

    public function toBr()
    {
        $this->value = nl2br($this->value);

        return $this;
    }

    public function deleteTag()
    {
        $this->value = strip_tags($this->value);

        return $this;
    }

    public function deleteRn()
    {
        $this->value = preg_replace('#[\r\n]+?#u', '', $this->value);

        return $this;
    }

    /*

    */

    public function space()
    {
        if (preg_match('#(\s|　)#', $this->value)) {
            self::errorSet("space");
        }

        return $this;
    }

    /*

    */

    public function required()
    {
        self::$required = true;

        if (empty($this->value) && $this->value !== 0 && $this->value !== "0") {
            self::errorSet("required");
        }

        return $this;
    }

    public function in()
    {
        return self::required();
    }

    public function notNull()
    {
        return self::required();
    }


    public function empty() // プログラムを見やすくするためのダミーメゾッド
    {
        return self::required();
    }

/*    public function null()
    {
        return self::null();
    }
*/

    /*

    */

    public function add($var)
    {
        if (! is_numeric($var)) {
            self::errorSet("add");
            return $this;
        }

        $this->value += $var;

        return $this;
    }

    /*

    */

    public function int()
    {
        $this->value = (int)$this->value;

        return $this;
    }

    public function float()
    {
        $this->value = (float)$this->value;

        return $this;
    }

    public function string()
    {
        $this->value = (string)$this->value;

        return $this;
    }

    /*

    */

    public function equal($var)
    {
        if ($this->value !== $var) {
            self::errorSet("equal");
        }

        return $this;
    }

    public function len($var)
    {
        if (mb_strlen($this->value) === $var) {
            self::errorSet("len");
        }

        return $this;
    }

    public function lenMax($var)
    {
        if (mb_strlen($this->value) > $var) {
            self::errorSet("lenMax");
        }

        return $this;
    }

    public function lenMin($var)
    {
        if (mb_strlen($this->value) < $var) {
            self::errorSet("lenMin");
        }

        return $this;
    }

    public function length($min, $max)
    {
        if (mb_strlen($this->value) < $min || mb_strlen($this->value) > $max) {
            self::errorSet("length");
        }

        return $this;
    }

    //
    public function max($var)
    {
        if (! is_numeric($var)) {
            self::errorSet("digit");
            return $this;
        }

        if ($this->value > (int)$var) {
            self::errorSet("max");
        }

        return $this;
    }

    public function min($var)
    {
        if (! is_numeric($var)) {
            self::errorSet("digit");
            return $this;
        }

        if ($this->value < (int)$var) {
            self::errorSet("min");
        }

        return $this;
    }

    public function minMax($min, $max)
    {
        if (! is_numeric($min) || ! is_numeric($max)) {
            self::errorSet("digit");
            return $this;
        }

        if ($this->value < (int)$min || $this->value > (int)$max) {
            self::errorSet("minMax");
        }

        return $this;
    }

    //
    public function email()
    {
        if (! filter_var($this->value, FILTER_VALIDATE_EMAIL)) {
            self::errorSet("email");
        }

        return $this;
    }

    public function pregMatch($name, $preg)
    {
        if (! preg_match('#'.$preg.'#', $this->value)) {
            self::errorSet("match:".$name);
        }

        return $this;
    }

    public function url()
    {
        if (! preg_match('#\Ahttps?://[\w\:\%\#\$\&\?\(\)\~\.\=\+\-\/\S]+\z#', $this->value)) {
            self::errorSet("url");
        }

        return $this;
    }

    public function alpha()
    {
        if (! preg_match('#\A[a-zA-Z]+\z#', $this->value)) {
            self::errorSet("alpha");
        }

        return $this;
    }

    public function digit()
    {
        if (! is_numeric($this->value)) {
            self::errorSet("digit");
        }

        // int()?

        return $this;
    }

    public function alphanumeric()
    {
        if (! preg_match('#\A[a-zA-Z0-9]+\z#', $this->value)) {
            self::errorSet("alphanumeric");
        }

        return $this;
    }

    /*

    */

    public function time()
    {
        $this->value = time();

        return $this;
    } // 仮組み

    public function dateNow()
    {
        $this->value = time();

        self::date();
    } // 仮組み

    public function date()
    {
        $this->value = date('Y年m月d日 H時i分s秒', $this->value);

        return $this;
    } // 仮組み

    /*

    */

    public function password()
    {
        if (! preg_match('#\A[a-zA-Z0-9\@\%\+\$\\\/\!\#\^\~\:\.\?\-\_]+\z#', $this->value)) {
            self::errorSet("password");
        }

        return $this;
    }

    // Make Password Hash.
    public function passwordHash()
    {
        $pass = 'password';
        $iv = '1234567890123456'; //16桁

        // 暗号化
        $this->value = openssl_encrypt(
            "Dummy".$this->value,
            'aes-256-cbc',
            $pass,
            OPENSSL_RAW_DATA,
            $iv
        );

        // Hash.
        $this->value = hash_hmac('sha256', $this->value, 'secret', false);

        return $this;
    }
}
