<?php

class UserIdentity extends CUserIdentity
{
    protected $id;

    protected $user;

    public function __construct($name, $password)
    {
        $this->username = $name;
        $this->password = $password;
    }

    public function authenticate()
	{
        $this->user = User::model()->findByName($this->username);
        if (!$this->user) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } elseif (!Hash::check($this->password, $this->user->password)) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } else {
            $this->id = $this->user->id;
            $this->username = $this->user->name;
            $this->errorCode = self::ERROR_NONE;
        }

		return !$this->errorCode;
	}

    public function getId()
    {
        return $this->id;
    }

    public function getUser()
    {
        return $this->user;
    }
}
