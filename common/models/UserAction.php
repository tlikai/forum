<?php

class UserAction extends ActiveRecord
{
    protected $timestamp = false;

    // flag enums
    const REPLY = 1;
    const LIKE = 2;
    const FOLLOW = 3;
    const BOOKMARK = 4;

    public static $flagLabels = array(
        self::REPLY => 'reply',
        self::LIKE => 'like',
        self::FOLLOW => 'follow',
        self::BOOKMARK => 'bookmark',
    );

    public static $flagAttributes = array(
        self::REPLY => 'reply_count',
        self::LIKE => 'like_count',
        self::FOLLOW => 'follow_count',
        self::BOOKMARK => 'bookmark_count',
    );

    public function tableName()
    {
        return '{{user_actions}}';
    }

    public function rules()
    {
        return array(
            array('flag, topic_id', 'required'),
            array('flag', 'in', 'range' => array_keys(static::$flagLabels)),
            array('flag, topic_id, user_id, reply_id', 'numerical'),
        );
    }

    public function init()
    {
        parent::init();

        $this->onAfterSave = function(CEvent $e) {
            if ($this->isNewRecord) {
                static::updateCount(1, $this->flag, $this->topic_id, $this->reply_id);

                // update topic last posted
                if ($this->flag == static::REPLY) {
                    Topic::model()->updateByPk($this->topic_id, array('last_post_at' => time(), 'last_post_by' => $this->user_id));
                }
            }
        };

        $this->onAfterDelete = function(CEvent $e) {
            static::updateCount(-1, $this->flag, $this->topic_id, $this->reply_id);
        };
    }

    public static function mark($flag, $user_id, $topic_id, $reply_id = 0)
    {
        if (static::hasMark($flag, $user_id, $topic_id, $reply_id)) {
            throw new InvalidRequestException('Has already marked.');
        }

        $model = new self;
        $model->attributes = array(
            'flag' => $flag,
            'user_id' => $user_id,
            'topic_id' => $topic_id,
            'reply_id' => $reply_id,
        );

        return $model->save();
    }

    public static function hasMark($flag, $user_id, $topic_id, $reply_id = 0)
    {
        if ($flag == static::REPLY) {
            return false;
        }

        return static::model()->exists('flag = :flag AND user_id = :user_id AND topic_id = :topic_id AND reply_id = :reply_id', array(
            ':flag' => $flag,
            ':user_id' => $user_id ?: Yii::app()->user->id,
            ':topic_id' => $topic_id,
            ':reply_id' => $reply_id,
        ));
    }

    public static function unMark($flag, $user_id, $topic_id, $reply_id = 0)
    {
        $model = static::model()->find('flag = :flag AND user_id = :user_id AND topic_id = :topic_id AND reply_id = :reply_id', array(
            ':flag' => $flag,
            ':user_id' => $user_id,
            ':topic_id' => $topic_id,
            ':reply_id' => $reply_id,
        ));

        if (!$model) {
            throw new NotFoundException;
        }

        return $model->delete();
    }

    public static function updateCount($count, $flag, $topic_id, $reply_id = 0)
    {

        $attribute = static::$flagAttributes[$flag];

        if ($reply_id && in_array($flag, array(static::LIKE, static::BOOKMARK))) {
            return Reply::model()->updateCounters(array($attribute => $count), 'topic_id =? AND id = ?', array($topic_id, $reply_id));
        }

        return Topic::model()->updateCounters(array($attribute => $count), 'id = ?', array($topic_id));
    }

    public static function deleteReplies($topic_id)
    {
        static::model()->deleteAllByAttributes(array('topic_id' => $topic_id));
        return Reply::model()->deleteAllByAttributes(array('topic_id' => $topic_id));
    }

    public static function __callStatic($method, $parameters)
    {
        $method = strtolower($method);
        if (in_array($method, array('reply', 'unreply', 'like', 'unlike', 'haslike', 'follow', 'unfollow', 'hasfollow', 'bookmark', 'unbookmark', 'hasbookmark'))) {
            $flag = $method;
            $action = 'mark';

            if (strncmp($method, 'un', 2) == 0) {
                $action = 'unMark';
                $flag = substr($method, 2);
            }

            if (strncmp($method, 'has', 3) == 0) {
                $action = 'hasMark';
                $flag = substr($method, 3);
            }

            $indexes = array_flip(static::$flagLabels);
            $args = array_merge(array($indexes[$flag]), $parameters);
            return call_user_func_array(array('UserAction', $action), $args);
        }

        throw new BadMethodCallException;
    }
}
