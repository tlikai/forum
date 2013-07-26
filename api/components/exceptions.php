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

    public function __construct($message = null, $code = 400)
    {
        $this->messages = $message;
        parent::__construct(null, $code);
    }

    public function getMessages()
    {
        return $this->messages;
    }
}
