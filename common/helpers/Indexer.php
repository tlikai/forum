<?php

if (!extension_loaded('scws')) {
    throw new Exception('scws extension required.');
}

Yii::import('common.extensions.YiiRedis.*');

class Indexer
{
    public static function add($type, $id, $title)
    {
        $words = static::getWords($title);
        foreach ($words as $word) {
            $key = static::getKey($type, $word['word']);
            $set = new ARedisSet($key);
            $set->add($id);
        }

        return count($words);
    }

    public static function remove($type, $id, $title)
    {
        $words = static::getWords($title);
        foreach ($words as $word) {
            $key = static::getKey($type, $word['word']);
            $set = new ARedisSet($key);
            $set->remove($id);
        }
    }

    public static function search($type, $query)
    {
        $words = static::getWords($query);

        $indexes = array();
        foreach ($words as $word) {
            $key = static::getKey($type, $word['word']);
            $indexes[] = $key;
        }

        array_unshift($indexes, 'result');
        call_user_func_array(array(Yii::app()->redis->client, 'sUnionStore'), $indexes);
        return Yii::app()->redis->sMembers('result');
    }

    public static function getLastQuery()
    {
        return Yii::app()->redis->sMembers('result');
    }

    public static function getKey($type, $word)
    {
        return $type . ':' . md5($word);
    }

    /**
     * Split the word
     *
     * return array
     */
    public static function getWords($title, $limit = 10)
    {
        $sc = scws_new();
        $sc->set_charset('utf8');
        $sc->set_rule(Yii::getPathOfAlias('common.data') . '/rules.ini');
        $sc->set_dict(Yii::getPathOfAlias('common.data') . '/dict.xdb');
        $sc->set_ignore(true);
        $sc->send_text($title);
        $words = $sc->get_tops($limit);
        $sc->close();

        return $words;
    }
}
