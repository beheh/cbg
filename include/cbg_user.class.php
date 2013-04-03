<?php

require('cbg_user_ban.class.php');
require('cbg_user_group.class.php');
require('cbg_user_message.class.php');
require('cbg_user_settlement.class.php');

class cbg_user extends cbg_database_object {

    protected $ban;
    protected $group;
    protected $group_temp_id;
    protected $group_temp;
    protected $settlements;

    function __construct($cbg) {
        parent::__construct($cbg, 'cbg_user');
        $this->ban = null;
    }

    function __toString() {
        return $this->getUsername();
    }

    public function update($until) {
        foreach ($this->getSettlements() as $settlement) {
            $settlement->update($until);
        }
        return true;
    }

    public function loadByUsername($name, $nocase = false) {
        $error = false;
        if (!$this->loadByValue('name', $name)) {
            $error = true;
        } else {
            if ($this->getUsername() !== $name)
                if (strtolower($this->getUsername()) != strtolower($name) || !$nocase)
                    $error = true;
        }
        if ($error) {
            throw new OutOfBoundsException('unknown username');
            return false;
        }
        return true;
    }

    /* Userdata */

    public function setUsername($name) {
        if (!$this->cbg->validUsername($name))
            throw new UnexpectedValueException('Ungültiger Benutzername');
        if ($name != $this->getUsername()) {
            $found = false;
            try {
              if($this->cbg->getUserByName($name, true))
                $found = true;
            }
            catch(Exception $e) {
            }
            if($found) {
                throw new UnexpectedValueException('Benutzername bereits vergeben');
            }
        }
        return $this->setValue('name', $name);
    }

    public function getUsername() {
        return $this->getValue('name');
    }

    public function setPassword($password) {
        if (!$this->cbg->validPassword($password))
            throw new UnexpectedValueException('Ungültiges Passwort');
        return $this->setValue('password', $this->cbg->hashPassword($password));
    }

    public function matchPassword($password) {
        return $this->getValue('password') == $this->cbg->hashPassword($password);
    }

    public function setEmail($email) {
        if (!$this->cbg->validEmail($email))
            throw new UnexpectedValueException('Ungültige E-Mail-Adresse');
        return $this->setValue('mail', $email);
    }

    public function getEmail() {
        return $this->getValue('mail');
    }

    public function setInvitedBy($user_id) {
        return $this->setValue('invited_by', $user_id);
    }

    public function getInvitedById() {
        return $this->getValue('invited_by');
    }

    public function getInvitedBy() {
        try {
            return $this->cbg->getUserById($this->getInvitedById());
        }
        catch(Exception $ex) {
            return false;
        }
    }

    public function setPoints($points) {
        return $this->setValue('points', $points);
    }

    public function getPoints() {
        return $this->getValue('points');
    }

    public function getRank() {
        return $this->cbg->getUserRank($this);
    }

    public function getLastIp() {
        return $this->getValue('lastip');
    }

    public function getLastUseragent() {
        return $this->getValue('lastuseragent');
    }

    public function getRegistrationDate() {
        return $this->getValue('registration', 0);
    }

    public function getLastLogin() {
        return $this->getValue('lastlogin', 0);
    }

    public function setInvites($invites) {
        return $this->setValue('invites', $invites);
    }

    public function grantInvite() {
        return $this->setValue('invites', $this->getValue('invites') + 1);
    }

    public function getInvites() {
        return $this->getValue('invites');
    }

    public function setGroup($group_id) {
        $this->setValue('group', $group_id);
        $this->getGroup(true);
        return true;
    }

    public function getGroupId() {
        return $this->getValue('group');
    }

    public function setTemporaryGroup($group = 0) {
        $this->group_temp_id = $group;
        if ($this->group_temp_id != 0)
            $this->getGroup(true);
        return true;
    }

    public function groupOverrideActive() {
        return $this->group_temp_id != 0 && $this->getValue('group') != $this->group_temp_id;
    }

    public function getGroup($reload = false, $no_override = false) {
        if ((!$this->group && !$this->groupOverrideActive() || $no_override) || (!$no_override && $this->groupOverrideActive() && !$this->group_temp) || $reload) {
            $id = $this->group_temp_id != 0 && !$no_override ? $this->group_temp_id : $this->getGroupId();
            $group = $this->cbg->getGroupById($id);
            if ($this->groupOverrideActive() && !$no_override) {
                $this->group_temp = $group;
            } else {
                $this->group = $group;
            }
            return $group;
        } else {
            if ($this->groupOverrideActive() && !$no_override) {
                return $this->group_temp;
            } else {
                return $this->group;
            }
        }
    }

    public function getSettlements() {
        return $this->cbg->getSettlementsByUser($this->getId());
    }

    public function can($right, $real = false) {
        //@todo Premiumfeatures?
        if ($right == 'user_settlement_rename') {
            return true;
        }
        if ($right == 'user_settlement_relocate') {
            return false;
        }
        if ($right == 'user_settlement_remove') {
            return true;
        }
        return $this->getGroup(false, $real)->can($right);
    }

    public function canInvite() {

    }

    public function has($identifier) {
        $object = $this->cbg->getObject($identifier);
        foreach ($object->getCondition() as $condition) {
            if (!$this->hasCondition($condition))
                return false;
        }
        return true;
    }

    public function hasCondition($condition) {
        $type = (string) $condition->type;
        $identifier = (string) $condition->identifier;
        $amount = (int) $condition->amount ? $condition->amount : 1;
        $condition->global = 0;
        switch ($type) {
            case 'object':
            case 'building':
                foreach ($this->getSettlements() as $settlement) {
                    if ($settlement->hasCondition($condition, true))
                        return true;
                }
                return false;
                break;
            case 'research':
                return false;
                break;
            default:
                throw new UnexpectedValueException('unknown condition type '.$type);
                break;
        }
        return true;
    }

    public function ban($reason, $duration = 0, $by = 0) {
        $ban = new cbg_user_ban($this->cbg);
        $ban->setUser($this->getId());
        $ban->setBy($by);
        $ban->setTime($this->cbg->getServertime());
        $ban->setComment($reason);
        $until = 0;
        if ($duration)
            $until = $duration + $this->cbg->getServertime();
        $ban->setUntil($until);
        return $ban->save();
    }

    public function getBan($reload = false) {
        if (($this->ban && $this->ban->exists() || $this->ban === false) && !$reload)
            return $this->ban;
        $ban = new cbg_user_ban($this->cbg);
        try {
            $ban->loadByUser($this->getId());
            $this->ban = $ban;
        } catch (OutOfBoundsException $ex) {
            $this->ban = false;
        }
        return $this->getBan();
    }

    public function removeBan() {
        $ban = $this->getBan();
        if ($ban) {
            $ban->remove();
            return true;
        }
        return false;
    }

    public function getMessages($read = true) {
        //@todo Caching?
        if ($read) {
            $res = $this->cbg->getPDO()->prepare("SELECT * FROM `cbg_user_message` WHERE (`to` = :id OR `to` = '0') AND `time` <= :time ORDER BY `time` DESC");
        } else {
            $res = $this->cbg->getPDO()->prepare("SELECT * FROM `cbg_user_message` WHERE (`to` = :id OR `to` = '0') AND `time` <= :time AND ( `read` = '0' OR `to` = '0' ) ORDER BY `time` DESC");
        }
        $res->execute(array(':id' => $this->getId(), ':time' => $this->cbg->getServertime()));
        $messages = array();
        foreach ($res->fetchAll() as $row) {
            $message = new cbg_user_message($this->cbg);
            $message->loadById($row['id']);
            $messages[] = $message;
        }
        return $messages;
    }

    public function getSupport() {
        //@todo Support implementieren
        $support = array();
        return $support;
    }

    public function getMessageCount($unread = false) {
        //@todo Caching
        if (!$unread) {
            $res = $this->cbg->getPDO()->prepare("SELECT * FROM `cbg_user_message` WHERE (`to` = :id OR `to` = '0') AND `time` < :time ORDER BY `time` DESC");
        } else {
            $res = $this->cbg->getPDO()->prepare("SELECT * FROM `cbg_user_message` WHERE (`to` = :id OR `to` = '0') AND `time` < :time AND (`read` = '0' OR `to` = '0') ORDER BY `time` DESC");
        }
        $res->execute(array(':id' => $this->getId(), ':time' => $this->cbg->getServertime()));
        $count = 0;
        while ($row = $res->fetch()) {
            $count++;
        }
        return $count;
    }

    public function sendMessage($to, $messagetext) {
        //@Todo In Cache schreiben?
        $message = new cbg_user_message($this->cbg);
        $message->setFrom($this->getId());
        $message->setTo($to);
        $message->setMessage($messagetext);
        $message->setTime($this->cbg->getServertime());
        $message->save();
        return $message;
    }

    public function getKeys() {
        return $this->cbg->getUserKeys($this);
    }

    /* Session */

    public function login() {
        $this->setValue('lastip', $_SERVER['REMOTE_ADDR']);
        $this->setValue('lastuseragent', $_SERVER['HTTP_USER_AGENT']);
        $this->setValue('logout', false);
        if (!$this->save())
            return false;
        $this->cbg->antiCheat($this);
        return true;
    }

    public function logout() {
        $this->setValue('logout', true);
        $this->setValue('lastlogin', $this->cbg->getLoginTime());
        return $this->save();
    }

    public function addHistory($type, $object, $details, $time) {
        if (!$this->exists())
            return false;
        return $this->cbg->addUserHistory($this, $type, $object, $details, $time);
    }

    public function getHistory($length = 0) {
        return $this->cbg->getUserHistory($this, $length);
    }

    public function isOnline() {
        if (!$this->exists())
            return false;
        if ($this->getBan())
            return false;
        if ($this->getValue('logout'))
            return false;
        return $this->lastOnline() > ($this->cbg->getServertime() - $this->cbg->getLoginKeep());
    }

    public function lastOnline() {
        return $this->getValue('lastactivity');
    }

    public function setActive() {
        if ($this->cbg->getServertime() && $this->lastOnline() != $this->cbg->getServertime()) {
            $this->setValue('lastactivity', $this->cbg->getServertime());
            return $this->save();
        }
        return true;
    }

}

?>
