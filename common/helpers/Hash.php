<?php

class Hash
{
    const ROUNDS = 8;

    public static function make($value, array $options = array())
    {
        $cost = isset($options['rounds']) ? $options['rounds'] : static::ROUNDS;
        return password_hash($value, PASSWORD_BCRYPT, array('cost' => $cost));
    }

    public static function check($value, $hash)
    {
        return password_verify($value, $hash);
    }
}
