<?php

class User extends ActiveRecord
{
    protected $hidden = array('password');

    public $newPassword;
    public $confirmPassword;

    public function tableName()
    {
        return '{{users}}';
    }

    public function rules()
    {
        return array(
            array('name, email, password', 'required'),
			array('name', 'length', 'min' => 2, 'max' => 20),
            array('name', 'match', 'pattern' => '/^[a-zA-Z0-9_\x{4e00}-\x{9fa5}]+$/u'),
            array('email', 'email'),
            array('password', 'length', 'min' => 6),
            array('follower_count, following_count, created_at, updated_at', 'numerical'),
            array('avatar', 'safe'),

            array('name, email', 'unique', 'className' => 'User', 'on' => 'create'),

            array('password, newPassword, confirmPassword', 'required', 'on' => 'updatePassword'),
            array('newPassword', 'compare', 'compareAttribute' => 'confirmPassword', 'on' => 'updatePassword'),
        );
    }

    public function init()
    {
        parent::init();

        $this->onBeforeSave = function(CEvent $e) {
            if (in_array($this->scenario, array('create', 'changePassword'))) {
                $this->password = Hash::make($this->password);
            }
        };
    }

    public function findByName($name)
    {
        return $this->findByAttributes(array('name' => $name));
    }
}
