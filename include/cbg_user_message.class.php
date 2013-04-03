<?php

class cbg_user_message extends cbg_database_object {

    function __construct($cbg) {
        parent::__construct($cbg, 'cbg_user_message');
    }

    function __toString() {
        return $this->getMessage();
    }

    public function getMessage() {
        return $this->getValue('message');
    }

    public function setMessage($message) {
        return $this->setValue('message', $message);
    }

    public function getTime() {
        return $this->getValue('time');
    }

    public function setTime($time) {
        return $this->setValue('time', $time);
    }

    public function isRead() {
        return $this->getValue('read');
    }

    public function read() {
        $this->setValue('read', 1);
        return $this->save();
    }

    public function getFrom() {
        return $this->cbg->getUserById($this->getValue('from'));
    }

    public function setFrom($from) {
        return $this->setValue('from', $from);
    }

    public function getTo() {
        return $this->cbg->getUserById($this->getValue('to'));
    }

    public function setTo($to) {
        return $this->setValue('to', $to);
    }

    public function isGlobal() {
        try {
            $this->getTo();
            return false;
        }
        catch(OutOfBoundsException $ex) {
            return true;
        }
    }

    public function onCommit() {
        $this->setValue('hash', $this->getHash());
    }

    public function getHash() {
        return sha1($this->getValue('from').$this->getValue('to').$this->getValue('message'));
    }

}

?>