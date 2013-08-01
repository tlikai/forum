<?php

class Notification extends ActiveRecord
{
    const FOLLOW = 1;
    const REPLY = 2;
    const MENTION = 3;

    public function tableName()
    {
        return '{{notifications}}';
    }

    public function rules()
    {
        return array(
            array('inbox', 'in', 'range' => array(static::FOLLOW, static::REPLY, static::MENTION)),
            array('read, inbox, user_id, sender_id, topic_id, reply_id', 'numerical'),
        );
    }

    public function init()
    {
        parent::init();

        $this->onAfterSave = function(CEvent $e) {
            if ($this->isNewRecord) {
                User::model()->updateCounters(array('notification_count' => 1), 'id = ?', array($this->user_id));
            } else {
                if ($this->read == 1) {
                    User::model()->updateCounters(array('notification_count' => -1), 'id = ?', array($this->user_id));
                }
            }
        };
    }

    public static function send($inbox, $to, $from, $topic_id, $reply_id = 0)
    {
        $notification = new self;
        $notification->attributes = array(
            'read' => 0,
            'inbox' => $inbox,
            'user_id' => $to,
            'sender_id' => $from,
            'topic_id' => $topic_id,
            'reply_id' => $reply_id,
        );

        return $notification->save();
    }

    protected function beforeSave()
    {
        if ($this->sender_id == $this->user_id) {
            return false;
        }

        return parent::beforeSave();
    }
}
