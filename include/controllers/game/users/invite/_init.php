<?php
if($user->can('admin_view')) {
    $linkpath[] = array('title' => 'Benutzer einladen', 'link' => $context);
}
else {
    $linkpath[] = array('title' => 'Spieler einladen', 'link' => $context);
}
if(!$this->cbg->getOpenRegistration() && !$user->can('user_add') && $this->cbg->getMaximumInvites() < 1 && $user->getInvites() < 1 && count($user->getKeys()) < 1) {
    cbg_output::displayError('403');
    return false;
}
?>