<?php
if($user->can('user_ban') && $user->getId() != $cur_user->getId() && ($user->can('user_edit_all') || !$cur_user->can('admin_view'))) {
    $ban = $cur_user->getBan();
    $unban = false;
    if(isset($_POST['form_user_ban_reason']) && isset($_POST['form_user_ban_duration'])) {
        $cur_user->ban($_POST['form_user_ban_reason'], $_POST['form_user_ban_duration'] * 24 * 60 * 60, $user->getId());
        $ban = $cur_user->getBan(true);
        $this->smarty->assign('form_user_ban_success', $this->getIcon('hook') . 'Der Benutzer wurde gebannt.');
    }
    if(isset($_POST['form_user_ban_unban_submit']) && $ban) {
        $ban->remove();
        $ban = $cur_user->getBan(true);
        $unban = true;
    }
    if($ban) {
        $linkpath[] = array('title' => 'Entbannen', 'link' => $context);
        $content .= '<h2>Benutzer ' . $cur_user->getUsername() . ' entbannen</h2>';
        $this->smarty->assign('form_user_ban_unban', true);
        $this->smarty->assign('form_user_ban_reason', $ban->getComment());
        $until = $ban->getUntil() ? date(cbg_output::getFormat('date'), $ban->getUntil()) . ' um ' . date(cbg_output::getFormat('time'), $ban->getUntil()) : 'Unbestimmt';
        $this->smarty->assign('form_user_ban_until', $until);
    } else {
        $linkpath[] = array('title' => 'Bannen', 'link' => $context);
        $content .= '<h2>Benutzer ' . $cur_user->getUsername() . ' bannen</h2>';
        if($unban) {
            $content .= '<p><strong class="success">' . $this->getIcon('hook') . 'Der Benutzer wurde entbannt.</strong></p>';
        }
    }
    $content .= $this->smarty->fetch('interface/form_user_ban.tpl');
}
?>