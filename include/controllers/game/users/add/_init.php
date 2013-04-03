<?php
$linkpath[] = array('title' => 'Benutzer erstellen', 'link' => $context);
if(!$user->can('user_add')) {
    $this->displayError('403');
    return true;
}
?>