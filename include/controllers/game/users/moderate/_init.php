<?php
$linkpath[] = array('title' => 'Moderation', 'link' => $context);
if(!$user->can('user_moderate')){
    cbg_output::displayError('403');
    return false;
}
?>