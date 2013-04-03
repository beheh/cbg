<?php
    try {
        $user = $this->cbg->getUserByName($params[0], false);
        $this->smarty->assign('username', $params[0]);
        $output = $this->smarty->fetch('cbg_forgot.tpl');
    } catch (OutOfBoundsException $ex) {
        $this->displayError('404');
        return true;
    }
?>