<?php

class TopicFlag extends ActiveRecord
{
    protected $timestamp = false;

    // flag enums
    const REPLY = 1;
    const LIKE = 2;
    const FOLLOW = 3;
    public static $flagAttributes = array(self::REPLY => 'reply_count', self::LIKE => 'like_count', self::FOLLOW => 'follow_count');

    public function tableName()
    {
        return '{{topic_flags}}';
    }

    public function rules()
    {
        return array(
            array('flag, topic_id', 'required'),
            array('flag', 'in', 'range' => array(static::REPLY, static::LIKE, static::FOLLOW)),
            array('flag, topic_id, user_id', 'numerical'),
        );
    }

    public function init()
    {
        parent::init();

        $this->onBeforeSave = function(CEvent $e) {
            if ($this->isNewRecord) {
                if (!$this->user_id) {
                    $this->user_id = Yii::app()->user->id;
                }
            }
        };

        $this->onAfterSave = function(CEvent $e) {
            if ($this->isNewRecord) {
                $this->incrementCount($this->flag, $this->topic_id);

                // update topic last posted
                if ($this->flag == static::REPLY) {
                    Topic::model()->updateByPk($this->topic_id, array('last_post_at' => time(), 'last_post_by' => $this->user_id));
                }
            }
        };

        $this->onAfterDelete = function(CEvent $e) {
            $this->decrementCount($this->flag, $this->topic_id);
        };
    }

    public function incrementCount($flag, $topic_id)
    {
        $attribute = static::$flagAttributes[$flag];
        return Topic::model()->updateCounters(array($attribute => 1), 'id = ?', array($topic_id));
    }

    public function decrementCount($flag, $topic_id)
    {
        $attribute = static::$flagAttributes[$flag];
        return Topic::model()->updateCounters(array($attribute => -1), 'id = ?', array($topic_id));
    }

    public static function mark($flag, $topic_id, $user_id = null)
    {
        if (static::hasMark($flag, $topic_id, $user_id)) {
            throw new InvalidRequestException('Has already liked.');
        }

        $model = new self;
        $model->attributes = array(
            'flag' => $flag,
            'topic_id' => $topic_id,
            'user_id' => $user_id,
        );

        return $model->save();
    }

    public static function hasMark($flag, $topic_id, $user_id = null)
    {
        if ($flag == static::REPLY) {
            return false;
        }

        return static::model()->exists('flag = :flag AND topic_id = :topic_id AND user_id = :user_id', array(
            ':flag' => $flag,
            ':topic_id' => $topic_id,
            ':user_id' => $user_id ?: Yii::app()->user->id,
        ));
    }

    public static function unMark($flag, $topic_id, $user_id = null)
    {
        $model = static::model()->find('flag = :flag AND topic_id = :topic_id AND user_id = :user_id', array(
            ':flag' => $flag,
            ':topic_id' => $topic_id,
            ':user_id' => $user_id ?: Yii::app()->user->id,
        ));

        if (!$model) {
            throw new NotFoundException;
        }

        return $model->delete();
    }

    // Mark reply
    public static function reply($topic_id, $user_id = null)
    {
        return static::mark(static::REPLY, $topic_id, $user_id);
    }

    public static function unReply($topic_id, $user_id = null)
    {
        return static::unMark(static::REPLY, $topic_id, $user_id);
    }

    // Mark like
    public static function like($topic_id, $user_id = null)
    {
        return static::mark(static::LIKE, $topic_id, $user_id);
    }

    public static function hasLike($topic_id, $user_id = null)
    {
        return static::hasMark(static::LIKE, $topic_id, $user_id);
    }

    public static function unLike($topic_id, $user_id = null)
    {
        return static::unMark(static::LIKE, $topic_id, $user_id);
    }

    // Mark follow
    public static function follow($topic_id, $user_id = null)
    {
        return static::mark(static::FOLLOW, $topic_id, $user_id);
    }

    public static function hasFollow($topic_id, $user_id = null)
    {
        return static::hasMark(static::FOLLOW, $topic_id, $user_id);
    }

    public static function unFollow($topic_id, $user_id = null)
    {
        return static::unMark(static::FOLLOW, $topic_id, $user_id);
    }
}
