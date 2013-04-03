<?php
$linkpath[] = array('title' => 'Gruppen', 'link' => $context);
if(!$user->can('user_edit_all')){
    cbg_output::displayError('403');
    return false;
}
?>
