<?php
    if ($this->cbg->authSession()) {
        $this->cbg->endSession();
        header('Location: '.ROOT.'login/?done');
        return false;
    } else {
        header('Location: '.ROOT.'login/?session');
        return false;
    }
?>