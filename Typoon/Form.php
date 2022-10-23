<?php

class Form
{
    /*

    */

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

    /*

    */

    public static function token()
    {
        $_SESSION['TyIpToken'] = self::ipToken();

        $_SESSION['TyFormToken'] = hash('ripemd160', microtime());
        return $_SESSION['TyFormToken'];
    }

    public static function tokenCheck($var)
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

    /*

    */

    public static function select($array, $no = -1, $count = 0)
    {
        $html = "";
        //$count = 0;

        foreach ($array as $value) {
            if ($count == $no) {
                $html .= "  <option value=\"{$count}\" selected>{$value}</option>\n";
            } else {
                $html .= "  <option value=\"{$count}\">{$value}</option>\n";
            }

            $count++;
        }

        return $html;
    }

    public static function selectDateNow($time = 0)
    {
        if ($time === 0) {
            $time = $_SERVER['REQUEST_TIME'];
        }

        $date = getdate($time);

        return self::selectDate($date["year"], $date["mon"], $date["mday"], $date["hours"], $date["minutes"]);
    }

    public static function dateToTime($y, $m, $d, $h, $i)
    {
        //
        if ($y < 2022 || $y > 2023) {
            $y = 2022;
        }

        if ($m < 1 || $m > 12) {
            $m = 4;
        }

        if ($d < 1 || $d > 31) {
            $d = 4;
        }

        if ($h < 0 || $h > 23) {
            $h = 4;
        }

        if ($i < 0 || $i > 59) {
            $i = 4;
        }

        return mktime($h, $i, 0, $m, $d, $y);
    }

    public static function selectDate($y, $m, $d, $h, $i)
    {
        //
        if ($y < 2022 || $y > 2023) {
            $y = 2022;
        }

        if ($m < 1 || $m > 12) {
            $m = 4;
        }

        if ($d < 1 || $d > 31) {
            $d = 4;
        }

        if ($h < 0 || $h > 23) {
            $h = 4;
        }

        if ($i < 0 || $i > 59) {
            $i = 4;
        }

        //
        $html = '<div class="row">';

        $html .= '<div class="col"><select class="form-select" name="year">'.Form::select(
            [
                    2022, 2023
                ],
            $y, 2022
        )."</select> 年 </div>";

        $html .= '<div class="col"><select class="form-select" name="month">'.Form::select(
            [
                    1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12
                ],
            $m, 1
        )."</select> 月 </div>";

        $html .= '<div class="col"><select class="form-select" name="day">'.Form::select(
            [
                    1, 2, 3, 4, 5, 6, 7, 8, 9, 10,
                    11, 12, 13, 14, 15, 16, 17, 18, 19, 20,
                    21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31
                ],
            $d, 1
        )."</select> 日 </div>";

        $html .= '<div class="col"><select class="form-select" name="hour">'.Form::select(
            [
                    0, 1, 2, 3, 4, 5, 6, 7, 8, 9,
                    10, 11, 12, 13, 14, 15, 16, 17, 18, 19,
                    20, 21, 22, 23
                ],
            $h
        )."</select> 時 </div>";

        $html .= '<div class="col"><select class="form-select" name="minute">'.Form::select(
            [
                    0, 1, 2, 3, 4, 5, 6, 7, 8, 9,
                    10, 11, 12, 13, 14, 15, 16, 17, 18, 19,
                    20, 21, 22, 23, 24, 25, 26, 27, 28, 29,
                    30, 31, 32, 33, 34, 35, 36, 37, 38, 39,
                    40, 41, 42, 43, 44, 45, 46, 47, 48, 49,
                    50, 51, 52, 53, 54, 55, 56, 57, 58, 59
                ],
            $i
        )."</select> 分 </div></div>";

        return $html;
    }
}
