<?php

class cbg_pdo_statement extends PDOStatement {

    private $pdo;

    protected function __construct(cbg_pdo $pdo) {
        $this->pdo = $pdo;
    }

    public function execute($params = array()) {
        $this->pdo->addQuery($this->queryString);
        $this->pdo->increaseQueryCount();
        return parent::execute($params);
    }

}

?>