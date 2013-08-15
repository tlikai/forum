<?php


class Input
{
    public static $input;

    public static function get($key, $default = null)
    {
        $input = static::all();
        return isset($input[$key]) ? $input[$key] : $default;
    }

    public static function has($key)
    {
        $input = static::all();
        return isset($input[$key]);
    }

    public static function only($keys)
    {
        $input = static::all();
        $keys = is_array($keys) ? $keys : func_get_args();

        $data = array();
        foreach ($keys as $key) {
            $data[$key] = isset($input[$key]) ? $input[$key] : null;
        }

        return $data;
    }

    public static function all()
    {
        if (static::$input === null) {
            if (isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] == 'json') {
                static::$input = (array)CJSON::decode(Yii::app()->request->getRawBody());
            } else {
                static::$input = $_REQUEST + Yii::app()->request->getRestParams();
            }
        }

        return static::$input;
    }
}
