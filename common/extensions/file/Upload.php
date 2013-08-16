<?php

class Upload extends CComponent
{
    public $savePath;

    public $allowTypes;

    public $allowSize;

    protected $file;

    protected $filePath;

    protected $fileName;

    protected $fullPath;

    const ERROR_EXCEED_SIZE = 1000;

    const ERROR_DISALLOW_TYPE = 1001;

    protected $error;

    protected static $errorMessages = array(
        UPLOAD_ERR_OK => 'Uploaded',
        UPLOAD_ERR_INI_SIZE => 'Size exceed in ini',
        UPLOAD_ERR_FORM_SIZE => 'Size exceed in form',
        UPLOAD_ERR_PARTIAL => 'File partially was uploaded',
        UPLOAD_ERR_NO_FILE => 'No file was uploaded',
        UPLOAD_ERR_CANT_WRITE => 'Failed to write',
        self::ERROR_EXCEED_SIZE => 'Size exceed',
        self::ERROR_DISALLOW_TYPE => 'Disallow file type',
    );

    /**
     *
     * @param string $name
     * @param array $options
     */
    public function __construct($name, array $options = array())
    {
        $this->file = CUploadedFile::getInstanceByName($name);
        $this->setOptions($options);
    }

    /**
     * set options
     *
     * @param array $options
     */
    public function setOptions(array $options)
    {
        foreach ($options as $key => $option) {
            $this->$key = $option;
        }
    }

    /**
     * Auto save the file
     *
     * @return boolean
     */
    public function save()
    {
        if (!is_writable($this->savePath)) {
            throw new Exception('Save path does not exists or not writable');
        }

        $dir = date('/Y/m/d');
        $this->filePath = $this->savePath . $dir;
        if (!is_dir($this->filePath)) {
            mkdir($this->filePath, 0777, true);
        }

        $this->fileName = Str::random(5) . uniqid(time()) . '.' . $this->file->extensionName;
        $this->fullPath = $this->filePath . '/' . $this->fileName;

        return $this->saveAs($this->fullPath);
    }

    /**
     * Save the file as
     *
     * @param string $path
     * @return boolean
     */
    public function saveAs($path)
    {
        if ($this->validate()) {
            return $this->file->saveAs($path);
        }
        return false;
    }

    /**
     * Validate upload file
     * 
     * @return boolean
     */
    public function validate()
    {
        if ($this->file === null) {
            $this->error = UPLOAD_ERR_NO_FILE;
        } elseif ($this->file->hasError) {
            $this->error = $this->file->error;
        } else {
            $type = CFileHelper::getMimeType($this->file->tempName, null, true);
            if (!in_array($type, $this->allowTypes)) {
                $this->error = self::ERROR_DISALLOW_TYPE;
            } elseif ($this->allowSize < $this->file->size) {
                $this->error = self::ERROR_EXCEED_SIZE;
            }
        }

        return $this->error === null;
    }

    /**
     * Get file base path
     *
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * Get file name
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Get file full path
     *
     * @return string
     */
    public function getFullPath()
    {
        return $this->fullPath;
    }

    /**
     * Get error code
     *
     * @return integer
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Get error message
     *
     * @return string
     */
    public function getMesssage()
    {
        return static::$errorMessages[$this->error];
    }
}

