<?php
require('cbg_pdo_statement.class.php');

class cbg_pdo extends PDO {

    private $queryCount;
    private $querys;

    public function __construct($dsn, $user = NULL, $pass = NULL, $options = NULL) {
        $this->queryCount = 0;
        $this->querys = array();

        parent::__construct($dsn, $user, $pass, $options);

        $this->setAttribute(PDO::ATTR_STATEMENT_CLASS, array('cbg_pdo_statement', array($this)));
        $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->exec('SET CHARACTER SET utf8');
    }

    public function addQuery($query) {
        $this->querys[] = $query;
    }

    public function getQuerys() {
        return $this->querys;
    }

    public function exec($sql) {
        $this->increaseQueryCount();
        return parent::exec($sql);
    }

    public function query($sql) {
        $this->increaseQueryCount();
        $args = func_get_args();
        return call_user_func_array(array($this, 'parent::query'), $args);
    }

    public function getQueryCount() {
        return $this->queryCount;
    }

    public function increaseQueryCount() {
        $this->queryCount++;
    }

}

?>