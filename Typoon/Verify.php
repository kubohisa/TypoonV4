<?php

Err::init();

class Err
{
    public static $error;
    public static $errorKey;
    public static $required;

    public static $key;
    public static $flag;

    //
    public static function set(string $key)
    {
        self::$error[self::$key][$key] = true;
        self::$errorKey[self::$key] = true;
        self::$flag = true;
    }

    public static function init()
    {
        self::$error = array();
        self::$errorKey = array();
        self::$required = array();

        self::$key = "";
        self::$flag = false;
    }

    //
    public static function flag(string $key)
    {
        if (self::$required[$key] === true) {
            return self::$errorKey[$key];
        } elseif ($_POST[$key] !== "") {
            return self::$errorKey[$key];
        }
        return false;
    }

    public static function required()
    {
        self::$required[self::$key] = true;
        return;
    }

    public static function empty()
    {
        self::$required[self::$key] = false;
        return;
    }
}

class Verify
{
    /*

    */

    private $value;

    /*

    */
    public static function set(string $key, string &$data): Verify
    {
        Err::$errorKey[$key] = false;
        Err::$required[$key] = false;

        return new Verify($key, $data);
    }

    private function __construct(string $key, string &$data)
    {
        $data = mb_convert_encoding($data, "UTF-8", "auto");
        $this->value = &$data;
        Err::$key = $key;
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
		foreach($array as $key => $value) {
			$array[$key] = preg_replace('#\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z#u', '', $value);
			if ($array[$key] !== "") $array[$key] = "　".$array[$key];
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
            Err::set("space");
        }

        return $this;
    }

    /*

    */

    public function required()
    {
        Err::required();

        if (empty($this->value) && $this->value !== 0 && $this->value !== "0") {
            Err::set("required");
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
        Err::empty();

        return $this;
    }

/*    public function null()
    {
        return self::null();
    }
*/

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
	
    public function len($var)
    {
        if (mb_strlen($this->value) === $var) {
            Err::set("len");
        }

        return $this;
    }

    public function lenMax($var)
    {
        if (mb_strlen($this->value) > $var) {
            Err::set("lenMax");
        }

        return $this;
    }

    public function lenMin($var)
    {
        if (mb_strlen($this->value) < $var) {
            Err::set("lenMin");
        }

        return $this;
    }

    public function length($min, $max)
    {
        if (mb_strlen($this->value) < $min || mb_strlen($this->value) > $max) {
            Err::set("length");
        }

        return $this;
    }

    //
    public function max($var)
    {
        if (! is_numeric($var)) {
            Err::set("digit");
			return $this;
        }

        if ($this->value > (int)$var) {
            Err::set("max");
        }

        return $this;
    }

    public function min($var)
    {
        if (! is_numeric($var)) {
            Err::set("digit");
			return $this;
        }

        if ($this->value < (int)$var) {
            Err::set("min");
        }

        return $this;
    }

    public function minMax($min, $max)
    {
        if (! is_numeric($min) || ! is_numeric($max)) {
            Err::set("digit");
			return $this;
        }

        if ($this->value < (int)$min || $this->value > (int)$max) {
            Err::set("minMax");
        }

        return $this;
    }

    //
    public function email()
    {
        if (! filter_var($this->value, FILTER_VALIDATE_EMAIL)) {
            Err::set("email");
        }

        return $this;
    }

    public function match($name, $preg)
    {
        if (! preg_match('#'.$preg.'#', $this->value)) {
            Err::set("match:".$name);
        }

        return $this;
    }

    public function url()
    {
        if (! preg_match('#\Ahttps?://[\w\:\%\#\$\&\?\(\)\~\.\=\+\-\/\S]+\z#', $this->value)) {
            Err::set("url");
        }

        return $this;
    }

    public function alpha()
    {
        if (! preg_match('#\A[a-zA-Z]+\z#', $this->value)) {
            Err::set("alpha");
        }

        return $this;
    }

    public function digit()
    {
        if (! is_numeric($this->value)) {
            Err::set("digit");
        }

        return $this;
    }

    public function alphanumeric()
    {
        if (! preg_match('#\A[a-zA-Z0-9]+\z#', $this->value)) {
            Err::set("alphanumeric");
        }

        return $this;
    }

    /*

    */

     public function password()
     {
         if (! preg_match('#\A[a-zA-Z0-9\@\%\+\$\\\/\!\#\^\~\:\.\?\-\_]+\z#', $this->value)) {
             Err::set("password");
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
