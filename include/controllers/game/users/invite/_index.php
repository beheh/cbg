<?php
$content .= '<h2>Einen oder mehrere Benutzer einladen</h2>';
$keyscreated = $user->getKeys();
$keyscount = count($keyscreated);
if($user->can('user_add')) {
    $this->smarty->assign('form_invite_admin', true);
    if($user->can('user_edit_all')) {
        $this->smarty->assign('form_invite_admin_all', true);
        $groups = array();
        foreach($this->cbg->getGroups() as $group) {
            $array = array();
            $array['id'] = $group->getId();
            $array['name'] = $group->getName();
            $array['selected'] = $group->getId() == $this->cbg->getDefaultGroup();
            $groups[] = $array;
        }
        $this->smarty->assign('form_invite_groups', $groups);
    }
    $keysmissing = isset($_POST['form_invite_request']) ? $_POST['form_invite_request'] : 0;
} else {
    $keysmax = $this->cbg->getMaximumInvites();
    $keysleft = $this->cbg->getMaximumInvites() + $user->getInvites();
    $keysmissing = $keysleft - $keyscount;
}
if(!$this->cbg->getOpenRegistration() || $user->can('user_edit_all')) {
    if($keysmissing > 0) {
        $group = $user->can('user_edit_all') && isset($_POST['form_invite_group']) ? $_POST['form_invite_group'] : $this->cbg->getDefaultGroup();
        $this->cbg->createKeys($keysmissing, $user->getId(), $group);
        $keyscreated = $user->getKeys();
    }
}
$keysusable = 0;
foreach($keyscreated as $key) {
    if($key['valid'])
        $keysusable++;
}
$this->smarty->assign('form_invite_invites_left', $keysusable);
$this->smarty->assign('form_invite_keys', $keyscreated);
$this->smarty->assign('form_invite_open_registration', $this->cbg->getOpenRegistration());
$this->smarty->assign('form_invite_invites_left', $keysusable);
$content .= $this->smarty->fetch('interface/form_invite.tpl');
?>