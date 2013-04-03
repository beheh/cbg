<?php

class cbg_user_group extends cbg_database_object {

    private $rights;

    function __construct($cbg) {
        parent::__construct($cbg, 'cbg_group');
    }

    function __toString() {
        return $this->getName();
    }

    public function getName() {
        return $this->getValue('name');
    }

    public function setName($name) {
        return $this->setValue('name', $name);
    }

    public function getDescription() {
        return $this->getValue('description');
    }

    public function setDescription($desc) {
        return $this->setValue('description', $desc);
    }

    public function getImage() {
        return $this->getValue('image');
    }

    public function setImage($image) {
        return $this->setValue('image', $image);
    }

    public function getMembers() {
        return $this->cbg->getUsersByGroup($this);
    }

    public function getMembercount() {
        return sizeof($this->getMembers());
    }

    public function can($right) {
        if(isset($this->rights))
            return in_array($right, $this->rights);
        $this->rights = array();
        $res = $this->cbg->getPDO()->prepare("SELECT `cbg_right`.`name` FROM `cbg_right`,`cbg_group_right` WHERE `cbg_group_right`.`group` = :group AND `cbg_group_right`.`right` = `cbg_right`.`id`");
        $res->execute(array(':group' => $this->getId()));
        foreach($res->fetchAll() as $row) {
            $this->rights[] = $row['name'];
        }
        return $this->can($right);
    }

}

?>