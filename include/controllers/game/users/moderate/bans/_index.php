<?php
if($user->can('user_ban')) {
    $linkpath[] = array('title' => 'Bans', 'link' => $context);
    $content .= '<h2>Gebannte Benutzer anzeigen</h2>';
    $results = 0;
    foreach($this->cbg->getUsers(false, true) as $cur_user) {
        /* @var $cur_user cbg_user */
        $ban = $cur_user->getBan();
        if(!$ban || !$ban->exists()) {
            continue;
        }
        if($cur_user->can('admin_view') && !$user->can('user_edit_all')) {
            continue;
        }
        $reason = $ban->getComment() ? cbg_output::parseOutput($ban->getComment(), true) : 'Keine Begründung';
        $email = $user->can('user_mail_access') && $cur_user->getEmail() ? ' ('.$cur_user->getEmail().')' : '';
        $until = $ban->getUntil() != 0 ? ' &raquo; '.date($this->getFormat('date'), $ban->getUntil()) : '';
        $content .= cbg_output::createBlock(ROOT.'game/users/show/'.$cur_user->getId().'/', $this->getUserDetailed($cur_user, true).$email.$until, $reason.' (von '.$this->getUserDetailed($ban->getBy(), true).').');
        $results++;
    }
    if(!$results) {
        $content .= '<p class="disabled">Keine gebannten Benutzer vorhanden.</p>';
    }
} else {
    $this->displayError('403');
    return true;
}
?>