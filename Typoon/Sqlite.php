<?php


use PDO;

class Sqlite
{
    private $d;

    // mysql
    public function connect($database)
    {
        if (isset($this->d)) {
            return $this->d;
        }

        try {
            $this->d = new PDO("sqlite:../app/data/{$database}.sql");
            $this->d->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->d->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return;
        } catch (Exception $e) {
            echo mb_convert_encoding($e->getMessage().PHP_EOL, 'UTF-8', 'auto');
            die;
        }
    }

    public function get()
    {
        return $this->d;
    }

    public function exec($sql, $array = null)
    {
        try {
            $st = $this->d->prepare($sql);
            $st->execute($array);
            return;
        } catch (Exception $e) {
            echo mb_convert_encoding($e->getMessage().PHP_EOL, 'UTF-8', 'auto');
            die;
        }
    }

    public function fetch($sql, $array = null)
    {
        try {
            $st = $this->d->prepare($sql);
            $st->execute($array);
            return $st->fetch();
        } catch (Exception $e) {
            echo mb_convert_encoding($e->getMessage().PHP_EOL, 'UTF-8', 'auto');
            die;
        }
    }

    public function fetchAll($sql, $array = null)
    {
        try {
            $st = $this->d->prepare($sql);
            $st->execute($array);
            return $st->fetchAll();
        } catch (Exception $e) {
            echo mb_convert_encoding($e->getMessage().PHP_EOL, 'UTF-8', 'auto');
            die;
        }
    }

    public function close()
    {
        $this->d = null;
    }
}
