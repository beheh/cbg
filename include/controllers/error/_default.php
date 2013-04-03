<?php
    if(!isset($params[0])) {
        header('Location: '.ROOT);
        exit();
    }
    $this->displayError($params[0]);
?>