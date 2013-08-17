<?php

class Str
{
    /**
     * Generate a random string
     *
     * @param integer $length
     * @return string
     */
    public static function random($length)
    {
        static $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= $chars[rand(0, strlen($chars) - 1)];
        }

        return $string;
    }

    /**
     * Match mention users
     *
     * @param string $content
     * @return array
     */
    public static function getMentionUserNames($content)
    {
        $names = array();

        $pattern = '/(^|\s)@([a-zA-Z0-9_\x{4e00}-\x{9fa5}]+)/u';
        if (preg_match_all($pattern, $content, $matches)) {
            foreach ($matches[2] as $name) {
                $names[] = $name;
            }
        }

        return $names;
    }
}
