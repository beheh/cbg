<?php

class cbg_user_settlement_building extends cbg_database_object {

    private $building;

    function __construct($cbg) {
        parent::__construct($cbg, 'cbg_user_settlement_building');
    }

    function __toString() {
        return $this->getMessage();
    }

    public function update($until) {
        if($this->getCompletion() != 0 && $this->getCompletion() < $until) {
            $this->upgrade();
        }
        return true;
    }

    public function isFree() {
        return $this->getValue('building') == '';
    }

    public function isBuilding() {
        $this->update($this->cbg->getServertime());
        return $this->getCompletion() != 0;
    }

    public function getBuilding() {
        if($this->isFree())
            return false;
        $this->building = new cbg_building($this->cbg, $this->getIdentifier());
        return $this->building;
    }

    public function getImage() {
        if($this->isFree())
            return false;
        $image = $this->getBuilding()->getLevelImage();
        if($image) {
            return $this->getBuilding()->getImage().Max(1, Min($this->getLevel(), $image));
        }
        return $this->getBuilding()->getImage();
    }

    public function getName() {
        if($this->isFree())
            return false;
        return $this->getBuilding()->getName();
    }

    public function getDescription() {
        if($this->isFree())
            return false;
        return $this->getBuilding()->getDescription();
    }

    public function setCompletion($time) {
        return $this->setValue('completion', $time);
    }

    public function getCompletion() {
        return $this->getValue('completion');
    }

    public function scheduleUpgrade($duration) {
        if($this->isFree())
            return false;
        $this->setCompletion($this->cbg->getServertime() + $duration);
        return true;
    }

    public function upgrade() {
        if($this->isFree())
            return false;
        if($this->getBuilding()->getMaximumLevel() == 0 || $this->getLevel() < $this->getBuilding()->getMaximumLevel()) {
            $this->setLevel($this->getLevel() + 1);
        }
        $this->writeHistory();
        $this->setCompletion(0);
        $this->save();
        return true;
    }

    public function build($identifier, $duration) {
        if(!$this->isFree())
            return false;
        $this->setIdentifier($identifier);
        $this->setLevel(0);
        if($duration == 0) {
            $this->upgrade();
        }
        else {
            $this->scheduleUpgrade($duration);
        }
        $this->save();
        return true;
    }

    public function writeHistory() {
        $settlement = new cbg_user_settlement($this->cbg);
        $settlement->loadById($this->getSettlement());
        $settlement->getOwner()->addHistory('building', $this->getId(), $this->getLevel(), $this->getCompletion());
        return true;
    }

    public function is($title) {
        if($this->isFree())
            return false;
        return $this->getBuilding()->is($title);
    }

    protected function setIdentifier($identifier) {
        return $this->setValue('building', $identifier);
    }

    public function getIdentifier() {
        return $this->getValue('building');
    }

    public function getOrder() {
        return $this->getValue('order');
    }

    public function setOrder($order) {
        return $this->setValue('order', $order);
    }

    public function getSettlement() {
        return $this->getValue('settlement');
    }

    public function setSettlement($settlement) {
        return $this->setValue('settlement', $settlement);
    }

    public function setLevel($level) {
        return $this->setValue('level', $level);
    }

    public function getLevel() {
        return $this->getValue('level');
    }

}

?>