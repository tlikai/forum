<?php

class ActiveRecord extends CActiveRecord implements IArrayable
{
    /**
     * hidden attributes fo toArray
     *
     * @see ActiveRecord::toArray
     * @var array
     */
    protected $hidden = array();

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
        return array_diff_key($attributes, array_flip($this->hidden));
    }

    /**
     * Convert model to string
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->toArray());
    }
}
