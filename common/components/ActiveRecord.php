<?php

class ActiveRecord extends CActiveRecord implements IArrayable
{
    public $extraAttributes = array();

    /**
     * hidden attributes fo toArray
     *
     * @see ActiveRecord::toArray
     * @var array
     */
    protected $hidden = array();

    /**
     * Auto fill time field
     *
     * @var boolean
     */
    protected $timestamp = true;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public static function model($className = null)
    {
        return parent::model($className ?: get_called_class());
    }

    public function init()
    {
        $this->onBeforeSave = function(){
            if ($this->timestamp) {
                $this->{static::UPDATED_AT} = time();
                if ($this->isNewRecord) {
                    $this->{static::CREATED_AT} = $this->{static::UPDATED_AT};
                }
            }
        };
    }

    /**
     * Find model or throw exception
     *
     * @param integer $id
     * @throws NotFoundException
     *
     * @return ActiveRecord
     */
    public function findOrFail($id)
    {
        $model = $this->findByPk($id);
        if ($model === null) {
            throw new NotFoundException;
        }

        return $model;
    }

    /**
     * 
     * @param integer $limit
     *
     * @return ActiveDataProvider
     */
    public function paginate($limit = null)
    {
        return new ActiveDataProvider($this, array(
            'pagination' => array(
                'pageSize' => $limit,
            ),
        ));
    }

    /**
     * Convert model to array
     *
     * @return array
     */
    public function toArray()
    {
        $attributes = $this->getAttributes();

        foreach($this->relations() as $name => $v) {
            if ($this->hasRelated($name)) {
                $relation = $this->getRelated($name);
                if (is_array($relation)) {
                    foreach ($relation as $key => $val) {
                        $relation[$key] = $val->toArray();
                    }
                    $attributes[$name] = $relation;
                } else {
                    $attributes[$name] = $relation->toArray();
                }
            }
        }

        $attributes = array_merge($attributes, $this->extraAttributes);

        return array_diff_key($attributes, array_flip($this->hidden));
    }

    public function getHidden()
    {
        return $this->hidden;
    }

    public function setHidden($attributes)
    {
        $attributes = is_array($attributes) ? $attributes : func_get_args();
        $this->hidden = $attributes;
    }
}
