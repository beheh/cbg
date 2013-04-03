<?php
if($user->can('admin_view')) {
    $option_users = array();
    if($user->can('user_view'))
        $option_users[] = cbg_output::createBlock($context.'search/', 'Benutzer suchen', 'Alle Benutzer anzeigen und nach einem bestimmten Benutzer suchen.');
    if($user->can('user_add'))
        $option_users[] = cbg_output::createBlock($context.'add/', 'Benutzer erstellen', 'Einen neuen Benutzer erstellen und ihm einer Gruppe zuweisen.');
    if($user->can('user_moderate'))
        $option_users[] = cbg_output::createBlock($context.'moderate/', 'Benutzer moderieren', 'Beschwerden zu Benutzern und statistische Vorschläge zu Verwarnungen und Bans anzeigen.');
    $option_users[] = cbg_output::createBlock($context.'invite/', 'Benutzer einladen', 'Einen oder mehrere neue Benutzer einladen.', !$this->cbg->getOpenRegistration() && !$user->can('user_add') && $user->getInvites() >= 0 && $this->cbg->getMaximumInvites() == 0 && count($user->getKeys()) == 0);
    if(!empty($option_users)) {
        $content .= '<h2>Benutzer und Einladungen</h2>';
        $content .= implode($option_users, PHP_EOL);
    }
    $options_groups = array();
    if($user->can('group_view'))
        $options_groups[] = cbg_output::createBlock($context.'groups/', 'Gruppen anzeigen', 'Vorhandene Gruppen anzeigen und ihre Berechtigungen zuweisen.');
    if($user->can('group_add'))
        $options_groups[] = cbg_output::createBlock($context.'groups/add/', 'Gruppe erstellen', 'Eine neue Gruppe erstellen und ihr Berechtigungen zuweisen.');
    if($user->can('user_edit_all')) {
        $options_groups[] = cbg_output::createBlock($context.'rights/', 'Berechtigungen anzeigen', 'Die verfügbaren Berechtigungen anzeigen und Gruppen zuweisen.');
        $options_groups[] = cbg_output::createBlock($context.'rights/test/', 'Berechtigungen testen', 'Die Berechtigungen einer bestimmten Gruppe testen.');
    }
    if(!empty($options_groups)) {
        $content .= '<h2>Gruppen und Berechtigungen</h2>';
        $content .= implode($options_groups, PHP_EOL);
    }
} else {
    $content .= '<h2>Spieler suchen</h2>';
    $form = new cbg_output_form('user_search', cbg_output::redirect_url($context, 0, 'search/'), '123');
    $form->search('username', '', null, array('placeholder' => 'Benutzername', 'results' => 0, 'required' => 'required'));
    $form->submit('Suchen');
    $content .= $form->display();
}


if(!$user->can('admin_view')) {
    $content .= '<h2>Spieler einladen</h2>';
    if($this->cbg->getOpenRegistration() || $this->cbg->getMaximumInvites() != 0 || $user->getInvites() > 0 || count($user->getKeys()) != 0) {
        if(!$this->cbg->getOpenRegistration()) {
            $content .= '<a href="'.$context.'invite/">Meine Einladungen</a> &raquo;';
        } else {
            $content .= '<a href="'.$context.'invite/">Einen Freund einladen</a> &raquo;';
        }
    } else {
        $content .= '<span class="disabled">Einladung nicht möglich.</span>';
    }
}

$content .= '<h2>Account</h2>';
$content .= cbg_output::createBlock($context.'settings/', 'Einstellungen', 'Deine Einstellungen anzeigen und bearbeiten.');
?>