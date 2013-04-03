<?php

class cbg_map {

    private $cbg;

    function __construct($cbg) {
        $this->cbg = $cbg;
    }

    public function getTile($x, $y) {
        return array('x' => $x, 'y' => $y);
    }

}

?>
