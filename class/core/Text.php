<?php

class Text
{
    private static $texts;

    public static function get($key, $data = null)
    {
        // if not $key
        if (!$key) {
            return null;
        }

        if ($data) {
            foreach ($data as $var => $value) {
                ${$var} = $value;
            }
        }

        // load config file (this is only done once per application lifecycle)
        if (!self::$texts) {
            // self::$texts = require('../application/config/texts.php');

            self::$texts = require DIR_ROOT . 'config/texts.php';
        }

        // check if array key exists
        if (!array_key_exists($key, self::$texts)) {
            // return null;
            return '!! LABEL NOT FOUND !!';
        }

        return self::$texts[$key];
    }
}
