<?php

class NotFoundException extends Exception
{
    public function __construct($message = null, $code = 404)
    {
        $message = $message ?: 'Not Found';
        parent::__construct($message, $code);
    }
}

class UnauthorizedException extends Exception
{
    public function __construct($message = null, $code = 401)
    {
        $message = $message ?: 'Unauthorized';
        parent::__construct($message, $code);
    }
}

class InvalidRequestException extends Exception
{
    public function __construct($message = null, $code = 400)
    {
        $message = $message ?: 'Invalid Request';
        parent::__construct($message, $code);
    }
}

class ValidationException extends Exception
{
    protected $messages;

    public function __construct($validator, $code = 400)
    {
        $this->messages = $validator->messages();
        parent::__construct($this->messages, $code);
    }

    public function getMessages()
    {
        return $this->messages;
    }
}
