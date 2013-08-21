<?php

class Topic extends ActiveRecord
{
    public $tagIds;

    protected $hidden = array('score', 'content');

    public function tableName()
    {
        return '{{topics}}';
    }

    public function rules()
    {
        return array(
            array('subject, content', 'required'),
            array('tagIds', 'required', 'on' => 'create'),
            array('subject, content', 'filter', 'filter' => 'trim'),
            array('created_by, last_post_at, last_post_by, score, like_count, reply_count, follow_count, created_at, updated_at', 'numerical'),
        );
    }

    public function relations()
    {
        return array(
            'user' => array(static::BELONGS_TO, 'User', 'created_by'),
            'lastPoster' => array(static::BELONGS_TO, 'User', 'last_post_by'),
            'replies' => array(static::HAS_MANY, 'Reply', 'topic_id'),
            'actions' => array(static::HAS_MANY, 'UserAction', 'topic_id'),
        );
    }

    public function behaviors()
    {
        return array(
            'search' => array(
                'class' => 'SearchBehavior',
                'searchAttribute' => 'subject',
            ),
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
                $this->last_post_at = $this->created_at;
                $this->last_post_by = $this->created_by;

                // check tags exists
                $this->tagIds = array_slice($this->tagIds, 0, 5);
                $criteria = new CDbCriteria;
                $criteria->addInCondition('id', $this->tagIds);
                $tagCount = Tag::model()->count($criteria);
                if ($tagCount != count($this->tagIds)) {
                    throw new NotFoundException('Inalid tags');
                }
            }
        };

        $this->onAfterSave = array($this, 'resolveTags');
        $this->onAfterSave = array($this, 'resolveMention');

        // follow topic
        $this->onAfterSave = function(CEvent $e) {
            if ($this->isNewRecord) {
                UserAction::follow($this->created_by, $this->id);
            }
        };

        $this->onBeforeDelete = function() {
            UserAction::deleteTopic($this->id);
        };
    }

    /**
     * scope tab
     *
     * @string $tablName
     * @return Topic
     */
    public function scopeTab($tabName)
    {
        if ($tagName == 'popular') {
            $this->dbCriteria->mergerWith(array(
                'order' => 'scope DESC',
            ));
        }

        if ($tagName == 'latest') {
            $this->dbCriteria->mergerWith(array(
                'order' => 'created_at DESC',
            ));
        }

        return $this;
    }

    public function scopeTags($tagNames)
    {
        if (is_string($tagNames)) {
            $tagNames = explode(',', $tagNames);
        }

        $tags = Tag::model()->findAllByAttributes(array('name' => $tagNames));
        $tagIds = array_map(function($tag){
            return $tag['id'];
        }, $tags);

        $topicTags = TopicTag::model()->findAllByAttributes(array('tag_id' => $tagIds));
        $topicIds = array_map(function($tag){
            return $tag['topic_id'];
        }, $topicTags);

        $criteria = new CDbCriteria;
        $criteria->addInCondition('t.id', $topicIds);
        $this->dbCriteria->mergeWith($criteria);

        return $this;
    }

    public function getFollowers()
    {
        return $this->actions(array(
            'condition' => 'flag = :flag',
            'params' => array(
                ':flag' => UserAction::FOLLOW,
            ),
        ));
    }

    public function resolveTags(CEvent $e)
    {
        if (!$this->isNewRecord) {
            TopicTag::model()->deleteAll('topic_id = ?', array($this->id));
        }

        foreach ($this->tagIds as $tagId) {
            $model = new TopicTag;
            $model->attributes = array(
                'tag_id' => $tagId,
                'topic_id' => $this->id,
            );
            $model->save();
        }
    }

    public function resolveMention(CEvent $e)
    {
        if (!$this->isNewRecord) {
            return null;
        }

        $names = Str::getMentionUserNames($this->content);
        $users = User::model()->findAllByAttributes(array('name' => $names));
        foreach ($users as $user) {
            if ($user && $user->id !== $this->created_by) {
                Notification::send(Notification::MENTION, $user->id, $this->created_by, $this->id);
            }
        }
    }

    public function toArray()
    {
        if (!in_array('tags', $this->hidden)) {
            $tags = array();
            $topicTags = TopicTag::model()->with('tag')->findAllByAttributes(array('topic_id' => $this->id));
            foreach ($topicTags as $topicTag) {
                $tags[] = $topicTag->tag->toArray();
            }

            $this->extraAttributes['tags'] = $tags;
        }

        return parent::toArray();
    }
}
