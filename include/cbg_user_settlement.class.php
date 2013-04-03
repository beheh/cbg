<?php
require('cbg_user_settlement_building.class.php');

class cbg_user_settlement extends cbg_database_object {

    function __construct($cbg) {
        parent::__construct($cbg, 'cbg_user_settlement');
    }

    function __toString() {
        return $this->getName();
    }

    public function update($until) {
        $this->updateSearch($until);
        foreach($this->getBuildings() as $building) {
            $building->update($until);
        }
        return true;
    }

    public function setOwner($owner) {
        return $this->setValue('owner', $owner);
    }

    public function getOwner() {
        try {
            return $this->cbg->getUserById($this->getValue('owner'));
        }
        catch(OutOfBoundsException $ex) {
            return false;
        }
    }

    public function setName($name) {
        return $this->setValue('name', $name);
    }

    public function setRandomName() {
        $animals = array('Wipf', 'Monster', 'Feuermonster', 'Zap', 'Drachen', 'Clonk');
        $materials = array('Holz', 'Stein', 'Gold', 'Flint', 'Kristall');
        $front = array($animals[rand(0, sizeof($animals) - 1)], $materials[rand(0, sizeof($materials) - 1)]);
        $end = array('hausen', 'heim', 'berg', 'burg', 'stadt', 'furt', 'fort');
        $name = $front[rand(0, sizeof($front) - 1)].$end[rand(0, sizeof($end) - 1)];
        $this->setName($name);
    }

    public function getName() {
        return $this->getValue('name');
    }

    /*public function has($identifier) {
        $object = $this->cbg->getObject($identifier);
        foreach($object->getCondition() as $condition) {
            if(!$this->hasCondition($condition))
                return false;
        }
        return true;
    }*/

    /*public function hasCondition($condition, $norecurse = false) {
        $type = (string) $condition->type;
        $identifier = (string) $condition->identifier;
        $amount = (int) $condition->amount ? $condition->amount : 1;
        $global = $condition->global ? true : false;
        if($global && $this->getOwner() && !$norecurse) {
            return $this->getOwner()->hasCondition($condition);
        }
        switch($type) {
            case 'object':
                return $this->getObjectCount($identifier) >= $amount;
                break;
            case 'building':
                $buildings = $this->getBuildings();
                if(!$buildings)
                    return false;
                foreach($buildings as $building) {
                    if($building->getIdentifier() == $identifier)
                        if($building->getLevel() >= $amount) {
                            return true;
                        }
                }
                return false;
                break;
            default:
                throw new UnexpectedValueException('unknown condition type '.$type);
                break;
        }
    }*/

    public function getBuildings() {
        return $this->cbg->getBuildingsBySettlement($this->getId());
    }

    /*public function updateSearch($until) {
        $save = false;
        if($this->getValue('search_left') != 0 && $this->getValue('search_left') - $until < 0) {
            $buildings = $this->getBuildings();
            try {
                $order = isset($buildings[0]) ? $buildings[0]->getOrder() - 1 : 0;
                $this->addBuilding('', $order);
                $this->setValue('search_left', 0);
                $save = true;
            }
            catch(UnexpectedValueException $ex) {
                
            }
        }
        if($this->getValue('search_right') != 0 && $this->getValue('search_right') - $until < 0) {
            $buildings = $this->getBuildings();
            try {
                $order = isset($buildings[sizeof($buildings) - 1]) ? $buildings[sizeof($buildings) - 1]->getOrder() + 1 : 0;
                $this->addBuilding('', $order);
                $this->setValue('search_right', 0);
                $save = true;
            }
            catch(UnexpectedValueException $ex) {

            }
        }
        if($save)
            $this->save();
        return true;
    }*/

    public function addBuilding($identifier, $order) {
        return $this->cbg->createBuilding($this, $identifier, $order);
    }

    /*public function getObjectCount($identifier) {
        if(isset($this->objects)) {
            if(isset($this->objects[$identifier])) {
                return $this->objects[$identifier];
            }
            else {
                return 0;
            }
        }
        $res = $this->cbg->getPDO()->prepare('SELECT `object`, `amount` FROM `cbg_user_settlement_object` WHERE `settlement` = :settlement');
        $res->execute(array(':settlement' => $this->getId()));
        $objects = array();
        $amount = 0;
        foreach($res->fetchAll() as $row) {
            $objects[$row['object']] = $row['amount'];
            if($row['object'] == $identifier)
                $amount = $row['amount'];
        }
        $this->objects = $objects;
        return $amount;
    }*/
}

?>