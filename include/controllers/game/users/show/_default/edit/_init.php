<?php
$linkpath[] = array('title' => 'Bearbeiten', 'link' => $context);
if(!$user->can('user_edit')) {
    cbg_output::displayError('403');
    return false;
}
?>
