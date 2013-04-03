<?php
$linkpath[] = array('title' => 'Berechtigungen', 'link' => $context);
if(!$user->can('user_edit_all')){
    cbg_output::displayError('403');
    return false;
}
?>
