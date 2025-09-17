<?php

class Order
{
    public const STATUS_QUEUED = 1;
    public const STATUS_OK = 2;
    public const STATUS_ERROR = 3;

    private $id;
    private $status;

    public function __construct($id = null, $status = self::STATUS_QUEUED)
    {
        $this->id = $id ?? self::uuidgen();
        $this->status = $status;
    }

    public static function fromArray(array $data) {
        return new self($data['id'], $data['status']);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getStatusAsString()
    {
        switch ($this->status) {
            case self::STATUS_QUEUED:
                return 'Queued';
            case self::STATUS_OK:
                return 'OK';
            case self::STATUS_ERROR:
                return 'Error';
        }
    }

    public static function randomStatus() {
        $randomNumber = mt_rand(1, 100);
        return ($randomNumber < 80) ? self::STATUS_OK : self::STATUS_ERROR;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function toArray() {
        return get_object_vars($this);
    }

    public static function uuidgen()
    {
        // https://stackoverflow.com/a/44504979/1657502
        // On Linux: uuidgen
        if (function_exists('com_create_guid') === true)
            return trim(com_create_guid(), '{}');

        $data = openssl_random_pseudo_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
