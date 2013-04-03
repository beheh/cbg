<?php
/**
 * cbg is a persistent browser-based game themed to the style of the
 * shareware game series 'Clonk' and tries to bring the unique feeling
 * of this game in an online experience.
 *
 * 'Clonk' is a registered trademark of RedWolfDesign GmbH.
 *
 * @category   Persistent browser-based game
 * @package    cbg
 * @author     Benedict Etzel <benedict.etzel@gmail.com>
 * @copyright  2009-2012 cbg
 * @version    0.6
 * @link       http://etzelbt.fornax.uberspace.de/cbg/
 */
require('cbg_pdo.class.php');
require('cbg_output.class.php');
require('cbg_database_object.class.php');
require('cbg_user.class.php');
require('cbg_object.class.php');
require('cbg_building.class.php');

class cbg {
    /* Locals */

    private $ready;
    private $project_name;
    private $project_version;
    private $currentuser;
    private $currentsettlement;

    /* Output */
    private $output;
    /* PDO */
    private $pdo;
    /* Config */
    private $config;
    private $dbconfig;
    private $dbconfig_save;
    /* Cache */
    private $cache;

    public function __construct() {
        $this->ready = false;
        require('config/config_core.php');
        if(!isset($config))
            exit('No valid config loaded.');
        $this->config = $config;

        //Project
        $this->project_name = $this->config['project']['name'];
        $this->project_version = $this->config['project']['version'];

        //Debugging
        if($this->config['runtime']['debug']) {
            error_reporting(E_ALL & ~E_NOTICE);
        } else {
            error_reporting(0);
        }

        //Timezone
        date_default_timezone_set($config['runtime']['timezone']);

        //Constants
        define('ROOT', $config['project']['root_url']);
        define('MAINTENANCE', $config['runtime']['maintenance']);

        if(MAINTENANCE == '' || $this->isSuperSession()) {

            //Database
            try {
                $this->pdo = new cbg_pdo($this->config['db']['driver'].':dbname='.$this->config['db']['database'].';host='.$this->config['db']['host'].';', $this->config['db']['user'], $this->config['db']['password']);
            } catch(PDOException $e) {
                return false;
            }

            //DB-Config
            $this->dbconfig = array();
            $res = $this->getPDO()->prepare('SELECT * FROM `cbg_config`');
            $res->execute();
            foreach($res->fetchAll() as $row) {
                $this->dbconfig[$row['config']] = $row['value'];
            }
            $this->dbconfig_save = $this->dbconfig;
        }

        //Cache
        $this->currentuser = false;
        $this->currentsettlement = false;

        $this->ready = true;

        return true;
    }

    /* Project */

    public function getProjectName() {
        return $this->project_name;
    }

    public function getProjectVersion() {
        return $this->project_version;
    }

    public function getProjectHash() {
        return sha1($this->getProjectName().$this->getProjectVersion());
    }

    public function getServertime() {
        return $_SERVER['REQUEST_TIME'];
    }

    public function getSalt() {
        return $this->config['auth']['salt'];
    }

    public function getRoot() {
        return $this->config['runtime']['root'];
    }

    public function getCopyright() {
        return $this->config['project']['copyright'];
    }

    public function isDebug() {
        return $this->config['runtime']['debug'] == true;
    }

    public function getLoginKeep() {
        $time = $this->getConfig('user_login_keep', 10 * 60);
        if($time < 30) {
            $this->setConfig('user_login_keep', 30);
            return 30;
        }
        return $time;
    }

    public function getLoginMax() {
        $time = $this->getConfig('user_login_max', 24 * 60 * 60);
        if($time < 180) {
            $this->setConfig('user_login_max', 180);
            return 180;
        }
        return $time;
    }

    public function getConfig($config = null, $default = 0) {
        if(!$config)
            return $this->dbconfig;
        if(isset($this->dbconfig[$config]))
            return $this->dbconfig[$config];
        $this->setConfig($config, $default);
        return $default;
    }

    public function setConfig($config, $value) {
        $this->dbconfig[$config] = $value;
        $this->saveConfig();
        return true;
    }

    public function saveConfig() {
        foreach($this->dbconfig as $config => $value) {
            if(!isset($this->dbconfig_save[$config])) {
                $res = $this->getPDO()->prepare('INSERT INTO `cbg_config` (`config`, `value`) VALUES ( :config , :value )');
                $res->execute(array(':config' => $config, ':value' => $value));
                $this->dbconfig_save[$config] = $value;
            } else {
                if($this->dbconfig_save[$config] == $value)
                    continue;
                $res = $this->getPDO()->prepare('UPDATE `cbg_config` SET `value` = :value WHERE `config` = :config');
                $res->execute(array(':config' => $config, ':value' => $value));
                $this->dbconfig_save[$config] = $value;
            }
        }
    }

    /* Defaults */

    public function getDefaultGroup() {
        return $this->getConfig('user_group_default', 1);
    }

    public function getMaximumInvites() {
        return $this->getConfig('user_invite_maximum');
    }

    public function getOpenRegistration() {
        return $this->getConfig('user_registration_open');
    }

    /* Cache */

    public function cache($type, $id, $data) {
        if(!$this->config['runtime']['cache'])
            return true;
        $this->cache[$type][$id] = $data;
        return true;
    }

    public function getCache($type, $id) {
        if(isset($this->cache[$type][$id]))
            return $this->cache[$type][$id];
        return false;
    }

    public function apiCall($cmd) {
        //@TODO API
        //throw new Exception('API is currently disabled.');
        $cmd = str_replace(array(';', '//', '/*', '#'), '', $cmd);
        //eval('$result = $this->'.$cmd.';');
        //return $result;
        return 'To be implemented.';
    }

    public function update($until = 0) {
        //@TODO Eventhandler
        if($until == 0)
            $until = $this->cbg->getServertime();
        $this->updateMaintenance();
        foreach($this->getUsers() as $user) {
            $user->update($until);
        }
        return true;
    }

    /**
     * Handles the output by initiating and displaying an instance of cbg_output.
     *
     * @return boolean
     */
    public function output() {
        if(!$this->output) {
            $this->output = new cbg_output($this);
        }
        if(!$this->pdo) {
            $this->output->displayError('database', true);
            return true;
        }
        if(!$this->ready) {
            $this->output->displayError('internal', true);
            return true;
        }
        $this->output->display();
        return true;
    }

    /**
     * Returns the database abstraction object of the current system instance.
     *
     * @return cbg_pdo
     */
    public function getPDO() {
        return $this->pdo;
    }

    /* Anticheat */

    public function antiCheat(cbg_user $user) {
        if($user->can('user_edit_all', true))
            return true;
        $res = $this->getPDO()->prepare('SELECT * FROM `cbg_user` WHERE (`lastip` = :lastip OR `lastuseragent` = :lastuseragent ) AND `id` != :id');
        $res->execute(array(':lastip' => $user->getLastIp(), ':lastuseragent' => $user->getLastUseragent(), ':id' => $user->getId()));
        while($row = $res->fetch()) {
            $score = 0;
            if($row['lastip'] == $row['lastip'])
                $score += 5;
            $cur_user = $this->getUserById($row['id']);
            if($cur_user->can('user_edit_all', true))
                continue;
            $score += ( strlen($cur_user->getUsername()) * 2 / levenshtein($user->getUsername(), $cur_user->getUsername()) - 2) / 2;
            if(levenshtein($user->getLastUseragent(), $cur_user->getLastUseragent()) < 3)
                $score += 3;
            $score = min(10, $score);
            if($score >= 5) {
                $res = $this->getPDO()->prepare('SELECT * FROM `cbg_anticheat` WHERE (`player1` = :user1 AND `player2` = :user2 ) OR (`player2` = :user1 AND `player1` = :user2 )');
                $res->execute(array(':user1' => $user->getId(), ':user2' => $cur_user->getId()));
                $row = null;
                foreach($res->fetchAll() as $row) {
                    if((($row['player1'] == $user->getId() && $row['player2'] == $cur_user->getId()) || $row['player2'] == $user->getId() && $row['player1'] == $cur_user->getId()) && $row['time'] + (24 * 60 * 60) < $this->getServertime()) {
                        $row = null;
                        continue;
                    } else {
                        break;
                    }
                }
                if($row) {
                    if($score > $row['score']) {
                        $res = $this->getPDO()->prepare('UPDATE `cbg_anticheat` SET `score` = :score WHERE `id` = :id');
                        $res->execute(array(':id' => $row['id'], ':score' => $score));
                    }
                } else {
                    $res = $this->getPDO()->prepare('INSERT INTO `cbg_anticheat` (`id`, `player1`, `player2`, `time`, `score`) VALUES (\'\', :user1 , :user2 , :time , :score )');
                    $res->execute(array(':user1' => $user->getId(), ':user2' => $cur_user->getId(), ':time' => $this->getServertime(), ':score' => $score));
                }
            }
        }
        return true;
    }

    public function getAntiCheat($count = 0, cbg_user $user = null) {
        //@todo Caching
        if($user) {
            $res = $this->getPDO()->prepare('SELECT `player1`, `player2`, `score`, `time` FROM `cbg_anticheat` WHERE `player1` = :user OR `player2` = :user ORDER BY `score`');
            $res->execute(array(':user' => $user->getId()));
        } else {
            $res = $this->getPDO()->prepare('SELECT `player1`, `player2`, `score`, `time` FROM `cbg_anticheat` ORDER BY `score`');
            $res->execute();
        }
        $users = array();
        foreach($res->fetchAll() as $row) {
            if(!isset($users[$row['player1']]) && !isset($users[$row['player2']])) {
                $users[$row['player1']] = array();
                $users[$row['player1']]['data'] = $row;
                $users[$row['player1']]['count'] = 1;
            } else if(isset($users[$row['player1']])) {
                if($row['time'] > $users[$row['player1']] && (!isset($users[$row['player2']]) || $row['time'] > $users[$row['player2']]['data']['time'])) {
                    $users[$row['player1']]['data'] = $row;
                }
                $users[$row['player1']]['count']++;
            } else if(isset($users[$row['player2']])) {
                if($row['time'] > $users[$row['player2']] && (!isset($users[$row['player1']]) || $row['time'] > $users[$row['player1']]['data']['time'])) {
                    $users[$row['player2']]['data'] = $row;
                }
                $users[$row['player2']]['count']++;
            }
        }
        $i = 0;
        $scores = array();
        foreach($users as $user) {
            $i++;
            if($count != 0 && $i > $count)
                break;
            try {
                $player1 = $this->getUserById($user['data']['player1']);
                $player2 = $this->getUserById($user['data']['player2']);
                if($player1->getBan() != null && $player2->getBan() != null)
                    continue;
                $scores[] = array('player1' => $player1, 'player2' => $player2, 'score' => sprintf('%1.1f', $user['data']['score']), 'time' => $user['data']['time'], 'count' => $user['count']);
            } catch(OutOfBoundsException $ex) {

            }
        }
        return $scores;
    }

    /* Sessions */

    public function setSessionData($value, $to) {
        $_SESSION[$value] = $to;
        return true;
    }

    public function startSession(cbg_user $user) {
        session_start();
        session_name('sid');
        $_SESSION['login'] = true;
        $_SESSION['user'] = $user->getId();
        $_SESSION['user_group_temp'] = 0;
        $_SESSION['user_settlement'] = 0;
        $_SESSION['project'] = $this->getProjectHash();
        $_SESSION['root'] = ROOT;
        $_SESSION['start'] = $this->getServertime();
        $_SESSION['ip'] = sha1($_SERVER['REMOTE_ADDR']);
        $_SESSION['useragent'] = sha1($_SERVER['HTTP_USER_AGENT']);
        $user->setActive();
        $user->login();
        return true;
    }

    public function authSession() {
        session_start();
        session_name('sid');
        $user = $this->getCurrentUser();
        if($user != false &&
                $_SESSION['project'] == $this->getProjectHash() &&
                $_SESSION['root'] == ROOT &&
                $_SESSION['ip'] == sha1($_SERVER['REMOTE_ADDR']) &&
                $_SESSION['useragent'] == sha1($_SERVER['HTTP_USER_AGENT']) &&
                $this->getServertime() - $_SESSION['start'] < $this->getLoginMax() &&
                $user->isOnline() &&
                (!$this->getActiveMaintenance() || $user->can('project_maintenance'))) {
            $user->setActive();
            return true;
        } else {
            $this->endSession();
            return false;
        }
    }

    public function endSession() {
        $user = $this->getCurrentUser();
        if($user)
            $user->logout();
        session_unset();
        session_destroy();
        return true;
    }

    public function isSuperSession() {
        //return $_GET['bypass'] == $this->config['runtime']['bypass'];
        return false;
    }

    /* Maintenance */

    /**
     * Returns an array with details of the next maintenance.
     *
     * @return array
     */
    public function getNextMaintenance($duration = 86400) {
        $this->updateMaintenance($this->getServertime());
        $res = $this->getPDO()->prepare('SELECT `id`, `from`, `until`, `reason`, `by` FROM `cbg_maintenance` WHERE `from` < :from');
        $res->execute(array('from' => $this->getServertime() + $duration));
        return $res->fetchAll();
    }

    public function getActiveMaintenance() {
        $maintenances = $this->getNextMaintenance(0);
        if(isset($maintenances[0]))
            return $maintenances[0];
        return false;
    }

    public function updateMaintenance() {
        //@todo Maintenance-Scheduler
        $res = $this->getPDO()->prepare('DELETE FROM `cbg_maintenance` WHERE `until` < :until');
        $res->execute(array('until' => $this->getServertime()));
        return true;
    }

    /* Userhandling */

    /**
     * Attempts to load the user of the current session.
     *
     * @return cbg_user
     */
    public function getCurrentUser() {
        if($this->currentuser)
            return $this->currentuser;
        if(isset($_SESSION['login']) && $_SESSION['login'] == true) {
            try {
                $user = $this->getUserById($_SESSION['user']);
                if(isset($_SESSION['user_group_temp']) && $_SESSION['user_group_temp'] != 0)
                    $user->setTemporaryGroup($_SESSION['user_group_temp']);
                $this->currentuser = $user;
                return $user;
            } catch(Exception $ex) {
                return false;
            }
        }
        return false;
    }

    /**
     * Returns the time, when the current user logged in.
     *
     * @return int
     */
    public function getLoginTime() {
        if($this->getCurrentUser())
            return $_SESSION['start'];
        return false;
    }

    public function getCurrentSettlement($reload = false) {
        if($this->currentsettlement && !$reload)
            return $this->currentsettlement;
        if(!$this->getCurrentUser())
            return false;
        if($_SESSION['user_settlement'] > 0) {
            try {
                $settlement = $this->getSettlementById($_SESSION['user_settlement']);
                if($settlement->getOwner() && $settlement->getOwner()->getId() != $this->getCurrentUser()->getId())
                    $this->currentsettlement = $settlement;
                return $settlement;
            } catch(OutOfBoundsException $ex) {
                return false;
            }
        } else {
            $settlements = $this->getSettlementsByUser($this->getCurrentUser()->getId(), 1);
            if($settlements) {
                $_SESSION['user_settlement'] = $settlements[0]->getId();
                return $this->getCurrentSettlement();
            } else {
                return false;
            }
        }
    }

    public function setCurrentSettlement($id) {
        $settlement = $this->getSettlementById($id);
        if($settlement->getOwner()->getId() != $this->getCurrentUser()->getId())
            return false;
        $_SESSION['user_settlement'] = $id;
        $this->getCurrentSettlement(true);
        return true;
    }

    /**
     * Attempts to load a user with the given id.
     *
     * @param int $id
     * @return cbg_user
     */
    public function getUserById($id) {
        $cache = $this->getCache('user', $id);
        if($cache && !$cache->groupOverrideActive())
            return $cache;
        $user = new cbg_user($this);
        $user->loadById($id);
        $this->cache('user', $id, $user);
        return $user;
    }

    /**
     * Attempts to count all online users.
     *
     * @return int
     */
    public function getUsersOnlineCount() {
        $res = $this->getPDO()->prepare('SELECT COUNT(*) AS `count` FROM `cbg_user` WHERE `logout` = 0');
        $res->execute(/* array('group' => $group->getId()) */);
        $row = $res->fetch();
        return $row['count'];
    }

    /**
     * Attempts to load all users from the given group.
     *
     * @param cbg_user_group $group
     * @return cbg_user[]
     */
    public function getUsersByGroup(cbg_user_group $group) {
        $res = $this->getPDO()->prepare('SELECT * FROM `cbg_user` WHERE `group` = :group');
        $res->execute(array('group' => $group->getId()));
        $users = array();
        foreach($res->fetchAll() as $row) {
            $user = new cbg_user($this);
            $user->loadByRow($row);
            $this->cache('user', $row['id'], $user);
            $users[] = $user;
        }
        return $users;
    }

    /**
     * Attempts to load a user with the given username and ignores case, if set.
     *
     * @param string $name
     * @param bool $nocase
     * @return cbg_user
     */
    public function getUserByName($name, $nocase = false) {
        $user = new cbg_user($this);
        $user->loadByUsername($name, $nocase);
        return $user;
    }

    /**
     * Attempts to load all users containing the given string, if present.
     * If no string is given and banned is true, only banned users are loaded.
     *
     * @param string $containing
     * @param bool $banned
     * @param int $from
     * @param int $limit
     * @return cbg_user[]
     */
    public function getUsers($containing = '', $banned = false, $from = 0, $limit = -1) {
        $params = array();
        if(!empty($containing)) {
            $query = 'SELECT * FROM `cbg_user` WHERE `name` LIKE :contains';
            $params[':contains'] = '%'.$containing.'%';
        } else {
            $query = 'SELECT `cbg_user`.* FROM `cbg_user`';
            if($banned) {
                $query .= ',`cbg_user_ban` WHERE `cbg_user_ban`.`user` = `cbg_user`.`id` ORDER BY `cbg_user_ban`.`time` DESC';
            }
        }
        $res = $this->getPDO()->prepare($query);
        $res->execute($params);
        $users = array();
        foreach($res->fetchAll() as $row) {
            $user = new cbg_user($this);
            $user->loadByRow($row);
            $this->cache('user', $user->getId(), $user);
            $users[] = $user;
        }
        return $users;
    }

    /**
     * Attempts to create a new user and returns it. The user is not inserted
     * into the database yet and still has to be commited, therefore no local id
     * is present yet.
     *
     * @param string $name
     * @param string $password
     * @param string $email
     * @param int $group
     * @param int $invited_by
     * @return cbg_user
     */
    public function newUser($name, $password, $email, $group = 0, $invited_by = 0) {
        $user = new cbg_user($this);
        $user->setUsername($name);
        $user->setPassword($password);
        $user->setEmail($email);
        $user->setInvites(-$this->getMaximumInvites());
        if(!$group)
            $group = $this->getDefaultGroup();
        $user->setGroup($group);
        if($invited_by)
            $user->setInvitedBy($invited_by);
        return $user;
    }

    /**
     * Attempts to load a group with the given id.
     *
     * @param int $id
     * @return cbg_user
     */
    public function getGroupById($id) {
        $cache = $this->getCache('group', $id);
        if($cache)
            return $cache;
        $group = new cbg_user_group($this);
        $group->loadById($id);
        $this->cache('group', $id, $group);
        return $group;
    }

    public function getSettlementById($id) {
        $cache = $this->getCache('settlement', $id);
        if($cache) {
            return $cache;
        }
        $settlement = new cbg_user_settlement($this);
        $settlement->loadById($id);
        $this->cache('settlement', $id, $settlement);
        return $settlement;
    }

    public function getSettlementsByUser($id, $limit = 0) {
        $query = 'SELECT * FROM `cbg_user_settlement` WHERE `owner` = :owner';
        if($limit > 0)
            $query .= ' LIMIT '.$limit;
        $res = $this->getPDO()->prepare($query);
        $res->execute(array(':owner' => $id));
        $settlements = array();
        foreach($res->fetchAll() as $row) {
            $settlement = new cbg_user_settlement($this);
            $settlement->loadByRow($row);
            $this->cache('settlement', $settlement->getId(), $settlement);
            $settlements[] = $settlement;
        }
        return $settlements;
    }

    /**
     * Attempts to load all groups.
     *
     * @return cbg_group[]
     */
    public function getGroups() {
        $res = $this->getPDO()->prepare('SELECT * FROM `cbg_group`');
        $res->execute();
        $groups = array();
        foreach($res->fetchAll() as $row) {
            $group = new cbg_user_group($this);
            $group->loadByRow($row);
            $this->cache('group', $group->getId(), $group);
            $groups[] = $group;
        }
        return $groups;
    }

    /* Support */

    public function getSupportTickets(cbg_user $user) {
        $tickets = array();
        return($tickets);
    }

    /* Validity */

    public function validUsername($name) {
        if(strlen($name) < $this->getConfig('user_registration_length_min', 3) || strlen($name) > $this->getConfig('user_registration_length_max', 12))
            return false;
        if($name != htmlspecialchars($name))
            return false;
        if(!preg_match('/^[a-z][a-z0-9_]+[a-z0-9]$/i', $name))
            return false;
        $res = $this->getPDO()->prepare('SELECT * FROM `cbg_blacklist_username`');
        $res->execute();
        foreach($res->fetchAll() as $row) {
            $str = $row['string'];
            if(stripos($name, $str) !== false)
                return false;
        }
        if(!$this->validString($name))
            return false;
        return true;
    }

    public function validEmail($mail) {
        if(!preg_match('/[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/', $mail))
            return false;
        return true;
    }

    public function validPassword($password) {
        if(strlen($password) < $this->getConfig('user_registration_password_length_min', 6) || strlen($password) > $this->getConfig('user_registration_password_length_max', 64))
            return false;
        if($password != htmlspecialchars($password))
            return false;
        return true;
    }

    public function validString($string) {
        $res = $this->getPDO()->prepare('SELECT * FROM `cbg_blacklist_word`');
        $res->execute();
        foreach($res->fetchAll() as $row) {
            $str = $row['string'];
            if(stripos($string, $str) !== false)
                return false;
        }
        return true;
    }

    public function hashPassword($password) {
        $str = $this->getSalt().$password;
        for($i = 0; $i < $this->config['auth']['iterations']; $i++) {
            $str = sha1($str);
        }
        return $str;
    }

    /* Key-Handling */

    public function createKey($by = 0, $group = 0) {
        if(!$group)
            $group = $this->getDefaultGroup();
        $length = 12;
        $characters = '123456789ABCDEFGHIJKLMNPQRSTUVXYZ';
        $letters = 'ABCDEFGHIJKLMNPQRSTUVXYZ';
        $key = '';
        while($this->verifyKey($key) || empty($key)) {
            $key = 'CBG';
            $key .= $group < 10 ? $group : 0;
            $key .= '-';
            for($p = 0; $p < $length; $p++) {
                if(!($p % 4) && $p)
                    $key .= '-';
                if(!($p % 4)) {
                    $key .= $letters[mt_rand(0, strlen($letters) - 1)];
                } else {
                    $key .= $characters[mt_rand(0, strlen($characters) - 1)];
                }
            }
        }
        $res = $this->getPDO()->prepare("INSERT INTO `cbg_key` (`id`, `by`, `key`, `valid`, `group`) VALUES (NULL, :by , :key , 1, :group )");
        $res->execute(array(':key' => $key, ':by' => $by, ':group' => $group));
        return $key;
    }

    public function createKeys($count, $by = 0, $group = 0) {
        $keys = array();
        for($i = 0; $i < $count; $i++) {
            $keys[] = $this->createKey($by, $group);
        }
        return $keys;
    }

    public function verifyKey($key) {
        $res = $this->getPDO()->prepare("SELECT `valid`, `by` FROM `cbg_key` WHERE `key` = :key");
        $res->execute(array(':key' => $key));
        $row = $res->fetch();
        if($row) {
            $user = $this->getUserById($row['by']);
            if($row['valid'] == 1 && !$user->getBan()) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    public function getKeyGroup($key) {
        $res = $this->getPDO()->prepare("SELECT `group` FROM `cbg_key` WHERE `key` = :key");
        $res->execute(array(':key' => $key));
        $row = $res->fetch();
        if($row) {
            return $row['group'];
        }
    }

    public function getKeyOwner($key) {
        $res = $this->getPDO()->prepare("SELECT `by` FROM `cbg_key` WHERE `key` = :key");
        $res->execute(array(':key' => $key));
        $row = $res->fetch();
        if($row) {
            return $row['by'];
        }
    }

    public function getUserKeys($user) {
        $res = $this->getPDO()->prepare("SELECT `key`, `valid`, `group` FROM `cbg_key` WHERE `by` = :by");
        $res->execute(array(':by' => $user->getId()));
        $keys = array();
        foreach($res->fetchAll() as $row) {
            try {
                $group = new cbg_user_group($this);
                $group->loadById($row['group']);
                $row['group_str'] = $group->getName();
            } catch(OutOfBoundsException $ex) {
                $row['group_str'] = '';
            }
            $keys[] = $row;
        }
        return $keys;
    }

    public function findKey($key) {
        $res = $this->getPDO()->prepare("SELECT `key` FROM `cbg_key` WHERE `key` = :key");
        $res->execute(array(':key' => $key));
        $row = $res->fetch();
        if($row) {
            return true;
        }
        return false;
    }

    public function useKey($key) {
        if(!$this->verifyKey($key))
            return false;
        $res = $this->getPDO()->prepare("UPDATE `cbg_key` SET `valid` = '0' WHERE `key` = :key");
        $res->execute(array(':key' => $key));
        return true;
    }

    /* Objects */

    public function getObject($identifier) {
        $object = new cbg_object($this, $identifier);
        return $object;
    }

    public function getBuilding($identifier) {
        $building = new cbg_building($this, $identifier);
        return $building;
    }

    public function getBuildingById($id) {
        $cache = $this->getCache('building', $id);
        if($cache) {
            return $cache;
        }
        $building = new cbg_user_settlement_building($this);
        $building->loadById($id);
        $this->cache('building', $id, $building);
        return $building;
    }

    public function createBuilding($settlement, $identifier, $order) {
        $building = new cbg_user_settlement_building($this);
        $building->setSettlement($settlement->getId());
        $building->setOrder($order);
        $building->build($identifier, 0);
        return $building;
    }

    public function getBuildingsBySettlement($id) {
        $cache = $this->getCache('settlement_buildings', $id);
        if($cache) {
            return $cache;
        }
        try {
            $buildings = array();
            $res = $this->getPDO()->prepare("SELECT * FROM `cbg_user_settlement_building` WHERE `settlement` = :settlement ORDER BY `order`");
            $res->execute(array(':settlement' => $id));
            foreach($res->fetchAll() as $row) {
                $building = new cbg_user_settlement_building($this);
                $building->loadByRow($row);
                $buildings[] = $building;
            }
            $this->cache('settlement_buildings', $id, $buildings);
            return $buildings;
        } catch(OutOfBoundsException $ex) {
            $this->cache('settlement_buildings', $id, false);
            return false;
        }
    }

    /* Safety */

    public function logAttack() {
        $this->authSession();
        $user = $this->getCurrentUser() ? $this->getCurrentUser()->getId() : 0;
        $server = '';
        $post = '';
        $get = '';
        $count = 0;
        foreach($_SERVER as $key => $value) {
            if($count)
                $server .= ', ';
            $server .= $key.' => '.$value;
            $count++;
        }
        $count = 0;
        foreach($_POST as $key => $value) {
            if($count)
                $server .= ', ';
            $post .= $key.' => '.$value;
            $count++;
        }
        $count = 0;
        foreach($_GET as $key => $value) {
            if($count)
                $server .= ', ';
            $get .= $key.' => '.$value;
            $count++;
        }
        $res = $this->getPDO()->prepare("INSERT INTO `cbg_attack` (`id`, `time`, `server`, `post`, `get`, `user`) VALUES (NULL, :time , :server , :post, :get , :user )");
        $res->execute(array(':time' => $this->getServertime(), ':server' => $server, ':post' => $post, ':get' => $get, ':user' => $user));
        return true;
    }

    public function getAttack() {
        //@todo Angriffsversuche ausgeben
        return array();
    }

    /* User history */

    public function addUserHistory(cbg_user $user, $type, $object, $details, $time) {
        $res = $this->getPDO()->prepare('INSERT INTO `cbg_user_history` (`id`, `user`, `time`, `type`, `object`, `details`) VALUES (NULL , :user , :time , :type , :object , :details )');
        $res->execute(array(':user' => $user->getId(), ':time' => $time, ':type' => $type, ':object' => $object, ':details' => $details));
        return true;
    }

    public function getUserHistory(cbg_user $user, $length) {
        $limit = $length != 0 ? ' LIMIT 0,'.$length : '';
        $res = $this->getPDO()->prepare('SELECT * FROM `cbg_user_history` WHERE `user` = :user AND `time` <= :time ORDER BY `time` DESC'.$limit);
        $res->execute(array(':user' => $user->getId(), ':time' => $this->getServertime()));
        return $res->fetchAll();
    }

    public function getUserRank(cbg_user $user) {
        //@todo Platzierungen und Highscore (User-ID ausreichend?)
        return 1;
    }

}

?>
