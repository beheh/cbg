<?php
$linkpath[] = array('title' => 'Testen', 'link' => $context);
if(!$user->can('user_edit_all')){
    cbg_output::displayError('403');
    return false;
}
?>
