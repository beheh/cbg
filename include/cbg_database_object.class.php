<?php

/**
 * @class cbg_database_object
 * @description Abstract class to handle loading and saving from and to database.
 */
abstract class cbg_database_object {

    private $loaded;
    private $exists;
    private $table;
    private $data;
    private $dbdata;
    protected $cbg;

    function __construct(cbg $cbg, $table) {
        $this->exists = false;
        $this->loaded = false;
        $this->table = $table;
        $this->data = array();
        $this->dbdata = array();
        $this->cbg = $cbg;
    }

    function __toString() {
        if(!isset($this->data['id']))
            return false;
        return $this->data['id'];
    }

    //stuff to do before comitting
    protected function onCommit() {
        return false;
    }

    public function exists() {
        return $this->exists;
    }

    public function getId() {
        if(!$this->exists())
            return false;
        if(!isset($this->data['id']))
            throw new UnexpectedValueException('Database object without id');
        return $this->data['id'];
    }

    public function getTable() {
        return $this->table;
    }

    protected function getValue($column, $default = null) {
        if(isset($this->data[$column]) && ($default === null || !empty($this->data[$column])))
            return $this->data[$column];
        if($default !== null) {
            $this->setValue($column, $default);
            $this->save();
            return $default;
        }
        return false;
    }

    protected function setValue($column, $value) {
        if($column == 'id')
            return false;
        if(!$this->exists || isset($this->data[$column])) {
            $this->data[$column] = $value;
            return true;
        }
        return false;
    }

    /* Database Abstraction */

    /* public function loadByTableIds($id, $columns, cbg_database_object[] $objects) {
      $query = 'SELECT * FROM `'.$this->getTable().'`'
      $query2 = '';
      foreach($objects as $index => $dbo) {
      $query .= ', ';
      $query2 .= ' AND ';
      $query .= '`'.addslashes($dbo->getTable()).'`';
      $query2 .= '`'.addslashes($dbo->getTable()).'`.`'.$columns.'` = `'.$this->getTable().'`.`id`';
      }
      $query .= ' WHERE `'.$this->getTable().'`.`id` = :'.$this->getTable().'_id'.$query2;
      $res = $this->cbg->getPDO()->prepare($query);
      $res->execute(array(':$this->getTable()_id' => $id));
      $row = $res->fetch();
      $results = array();
      foreach($row as $column) {
      foreach($objects as $index => $dbo) {
      $pos = strpos($column, $dbo->getTable();
      if($pos === 0) {
      $results[$index][substr($column, strlen($dbo->getTable())+1)] = $column;
      }
      }
      }
      foreach($objects as $index => $dbo) {
      $dbo->loadByRow($results[$index]);
      }
      return true;
      } */

    public function loadById($id) {
        if(!is_numeric($id))
            throw new UnexpectedValueException('non-numeric id supplied');
        if($id < 1)
            throw new OutOfBoundsException('id '.$id.' in table '.$this->getTable().' not found');
        return $this->loadByValue('id', $id);
    }

    public function loadByRow($row) {
        foreach($row as $column => $value) {
            $this->data[$column] = $value;
            $this->dbdata[$column] = $value;
        }
        $this->loaded = true;
        $this->exists = true;
        return $this->getId();
    }

    protected function loadByValue($column, $value) {
        $column = addslashes($column);
        $res = $this->cbg->getPDO()->prepare('SELECT * FROM `'.$this->getTable().'` WHERE `'.$column.'` = :'.$column);
        $res->execute(array(':'.$column => $value));
        $row = $res->fetch();
        if(!empty($row)) {
            return $this->loadByRow($row);
        }
        throw new OutOfBoundsException($column.' '.$value.' in table '.$this->getTable().' not found');
    }

    protected function loadByValues($columns, $values) {
        $query = 'SELECT * FROM `'.$this->getTable().'` WHERE ';
        $query_vals = array();
        $count = 0;
        foreach($columns as $index => $column) {
            if($count)
                $query .= ' AND ';
            $query .= '`'.$column.'` = :'.$column;
            $query_vals[':'.$column] = $values[$index];
            $count++;
        }
        $res = $this->cbg->getPDO()->prepare($query);
        $res->execute($query_vals);
        $row = $res->fetch();
        if(!empty($row)) {
            return $this->loadByRow($row);
        }
        throw new OutOfBoundsException('values in table '.$this->getTable().' not found');
    }

    public function save() {
        $this->onCommit();
        if($this->exists()) {
            $query = 'UPDATE `'.$this->getTable().'` SET';
            $count = 0;
            $values = array();
            foreach($this->data as $column => $data) { //build querystring and values
                if($data == $this->dbdata[$column])
                    continue; //nothing modified = no update
                if($count > 0)
                    $query .= ' ,';
                $count++;
                $query .= ' `'.$column.'` = :'.$column;
                $values[':'.$column] = $data; //seperate out valuearray
            }
            if(!$count)
                return true; //nothing changed = database up to date
            $query .= ' WHERE `id` = :id';
            $id = $this->getValue('id');
            if(!$id)
                return false;
            $values[':id'] = $id;
            $res = $this->cbg->getPDO()->prepare($query);
            return $res->execute($values);
        } else { //row doesn't exist
            $query = 'INSERT INTO `'.$this->getTable().'` (';
            $query_values = 'VALUES (';
            $values = array();
            $count = 0;
            foreach($this->data as $column => $data) { //build querystring and values
                if($column == 'id')
                    continue;
                if($count > 0) {
                    $query .= ' ,';
                    $query_values .= ',';
                }
                $count++;
                $query .= '`'.$column.'`';
                $query_values .= ' :'.$column.' ';
                $values[':'.$column] = $data;
            }
            $query .= ')';
            $query_values .= ')';
            $res = $this->cbg->getPDO()->prepare($query.' '.$query_values);
            $res->execute($values);
            $error_info = $res->errorInfo();
            if(isset($error_info[1]))
                throw new UnexpectedValueException('could not insert data into '.$this->getTable().': '.$error_info[2]);
            return $this->loadById($this->cbg->getPDO()->lastInsertId()); //reload all data
        }
    }

    public function remove() {
        if(!$this->exists)
            return true; //not inserted = nothing to remove
        $res = $this->cbg->getPDO()->prepare('DELETE FROM '.$this->getTable().' WHERE `id` = :id');
        if($res->execute(array(':id' => $this->getId()))) {
            $this->data = array();
            $this->dbdata = array();
            $this->exists = false;
            return true;
        }
        return false;
    }

}

?>
