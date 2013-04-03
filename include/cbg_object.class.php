<?php

class cbg_object {

    private $cbg;
    private $identifier;
    private $data;

    function __construct(cbg $cbg, $identifier) {
        $this->cbg = $cbg;
        $this->identifier = $identifier;
        $file = 'data/object/'.$identifier.'.xml';
        if(!file_exists('include/'.$file))
            throw new OutOfBoundsException('object '.$identifier.' doesn\'t exist');
        $xml = file_get_contents($file, FILE_USE_INCLUDE_PATH);
        $this->data = new SimpleXMLElement($xml);
    }

    function __toString() {
        return $this->getName();
    }

    protected function getValue($data) {
        return $this->data->$data;
    }

    public function getName() {
        return (string) $this->getValue('name');
    }

    public function getImage() {
        return $this->getValue('image');
    }

    public function getDescription() {
        return $this->getValue('description');
    }

    public function getCondition() {
        return $this->getValue('condition');
    }

}

?>