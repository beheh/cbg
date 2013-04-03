<?php

class cbg_user_ban extends cbg_database_object {

    private $by;
    private $user;

    function __construct($cbg) {
        parent::__construct($cbg, 'cbg_user_ban');
    }

    public function loadByUser($id) {
        while($this->loadByValue('user', $id)) {
            if($this->getUntil() != 0 && $this->getUntil() < $this->cbg->getServertime()) {
                $this->remove();
            }
            else {
                return;
            }
        }
    }

    public function setComment($comment) {
        return $this->setValue('comment', $comment);
    }

    public function getComment() {
        return $this->getValue('comment');
    }

    public function setBy($by) {
        return $this->setValue('by', $by);
    }

    public function getBy() {
        if($this->by)
            return $this->by;
        $this->by = $this->cbg->getUserById($this->getValue('by'));
        return $this->getBy();
    }

    public function getTime() {
        return $this->getValue('time');
    }

    public function setTime($time) {
        return $this->setValue('time', $time);
    }

    public function setUser($user) {
        return $this->setValue('user', $user);
    }

    public function getUser() {
        if($this->user)
            return $this->user;
        $this->user = $this->cbg->getUserById($this->getValue('user'));
        return $this->getUser();
    }

    public function setUntil($until) {
        return $this->setValue('until', $until);
    }

    public function getUntil() {
        return $this->getValue('until');
    }

}

?>