<?php

class Reply extends ActiveRecord
{
    public function tableName()
    {
        return '{{replies}}';
    }

    public function rules()
    {
        return array(
            array('topic_id, content', 'required'), 
            array('created_by, like_count, created_at, updated_at', 'numerical'),
        );
    }

    public function relations()
    {
        return array(
            'topic' => array(self::BELONGS_TO, 'Topic', 'topic_id'),
        );
    }

    public function init()
    {
        parent::init();

        $this->onBeforeSave = function(CEvent $e) {
            if ($this->isNewRecord) {
                if (!$this->created_by) {
                    $this->created_by = Yii::app()->user->id;    
                }
            }
        };

        $this->onAfterSave = array($this, 'resolveMention');
        $this->onAfterSave = function(CEvent $e) {
            if ($this->isNewRecord) {
                UserAction::reply($this->created_by, $this->topic_id, $this->id);

                // notify topic followers
                foreach ($this->topic->getFollowers() as $follower) {
                    if ($follower->user_id != Yii::app()->user->id) {
                        Notification::send(Notification::FOLLOW, $follower->user_id, $this->created_by, $this->topic_id, $this->id);
                    }
                }
            }
        };

        $this->onAfterDelete = function(CEvent $e) {
            UserAction::unReply($this->created_by, $this->topic_id, $this->id);
            UserAction::deleteReply($this->id);
        };
    }

    public function scopeTopic($id)
    {
        $this->dbCriteria->compare('topic_id', $id);
        return $this;
    }

    public function resolveMention()
    {
        if (!$this->isNewRecord) {
            return null;
        }

        $names = Str::getMentionUserNames($this->content);
        $users = User::model()->findAllByAttributes(array('name' => $names));
        foreach ($users as $user) {
            if ($user && $user->id !== $this->created_by) {
                Notification::send(Notification::MENTION, $user->id, $this->created_by, $this->topic_id, $this->id);
            }
        }
    }
}
