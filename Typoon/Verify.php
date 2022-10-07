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
        $this->value = preg_replace('#\A[\p{C}\p{Z}]++?(　|)|[\p{C}\p{Z}]#u', '', $this->value);

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

        if (empty($this->value)) {
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
    //
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
        }

        if ($this->value < (int)$var) {
            Err::set("min");
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
        if (! preg_match('#\Ahttps?://[\w/:%#\$&\?\(\)~\.=\+\-]+\z#', $this->value)) {
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
        if (! preg_match('#\A[0-9]+\z#', $this->value)) {
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

    /*

    */

    // Form.
    public static function mode()
    {
        if (isset($_POST['mode'])) {
            return trim($_POST['mode']);
        }
        return "";
    }

    public static function ipToken()
    {
        $ip = "";
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return hash(
            'ripemd160',
            $ip.$_SERVER['HTTP_USER_AGENT'].$_SERVER['HTTP_ACCEPT_LANGUAGE']
        );
        // ブラウザーの情報等を保存せずにハッシュ化
        // スマホなどはipアドレスが変化する可能性があるので、フォーム作成時ハッシュ作成
    }

    public static function formToken()
    {
        $_SESSION['TyIpToken'] = self::ipToken();

        $_SESSION['TyFormToken'] = hash('ripemd160', microtime());
        return $_SESSION['TyFormToken'];
    }

    public static function formTokenCheck($var)
    {
        //
        if (empty($_SESSION['TyIpToken'])) {
            return false;
        }

        $ipcheck = $_SESSION['TyIpToken'];
        unset($_SESSION['TyIpToken']);

        //
        if (empty($_SESSION['TyFormToken'])) {
            return false;
        }

        $check = $_SESSION['TyFormToken'];
        unset($_SESSION['TyFormToken']);

        if ($check === $var && $ipcheck === self::ipToken()) {
            return true;
        }
        return false;
    }
}
