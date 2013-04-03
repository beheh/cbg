<?php

class cbg_building {

    private $cbg;
    private $identifier;
    private $data;

    function __construct(cbg $cbg, $identifier) {
        $this->cbg = $cbg;
        $this->identifier = $identifier;
        $file = 'data/building/'.$identifier.'.xml';
        if(!file_exists('include/'.$file))
            throw new OutOfBoundsException('building '.$identifier.' doesn\'t exist');
        $xml = file_get_contents($file, FILE_USE_INCLUDE_PATH);
        $this->data = new SimpleXMLElement($xml);
    }

    protected function getValue($data) {
        return $this->data->$data;
    }

    public function getIdentifier() {
        return $this->identifier;
    }

    public function getName() {
        return $this->getValue('name');
    }

    public function getImage() {
        return $this->getValue('image');
    }

    public function is($title) {
        foreach($this->getValue('is') as $is) {
            if($is != $title && $is != 'all')
                continue;
            return true;
        }
        return false;
    }

    public function getLevelImage() {
        return $this->getValue('image_level');
    }

    public function getMaximumLevel() {
        return $this->getValue('level');
    }

    public function getDescription() {
        return $this->getValue('description');
    }

    public function getCondition() {
        return $this->getValue('condition');
    }

}

?>