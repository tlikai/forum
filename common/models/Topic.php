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
            array('created_by, last_post_at, last_post_by, score, like_count, reply_count, follow_count, created_at, updated_at', 'numerical'),
        );
    }

    public function relations()
    {
        return array(
            'user' => array(static::BELONGS_TO, 'User', 'created_by'),
            'lastPoster' => array(static::BELONGS_TO, 'User', 'last_post_by'),
            'replies' => array(static::HAS_MANY, 'Reply', 'topic_id'),
        );
    }

    public function init()
    {
        parent::init();

        $this->onBeforeSave = function(CEvent $e){
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

        $this->onBeforeDelete = function() {
            UserAction::deleteReplies($this->id);
        };
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
