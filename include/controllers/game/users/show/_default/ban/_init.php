<?php
if(!$user->can('user_ban')) {
    cbg_output::displayError('403');
    return false;
}
?>
