<?php
if($user->can('admin_view')) {
    $admin = cbg_output::createBlock($context.'users/', 'Benutzer und Gruppen', 'Neue Benutzer und Gruppen anlegen und verwalten.', !$user->can('user_add') && !$user->can('user_edit') && !$user->can('user_moderate'));
    $admin .= cbg_output::createBlock($context.'messages/', 'Nachrichten und Support', 'Private Nachrichten und Supportanfragen verwalten.', false);
    if($user->can('project_settings')) {
        $admin .= cbg_output::createBlock($context.'settings/', 'Spiel- und Projekteinstellungen', 'Einstellungen des Spiels anzeigen und verändern.', !$user->can('project_settings'));
    }
    $this->smarty->assign('gui_dashboard_administration', $admin);
}
else {
    cbg_output::redirect($context, 0, 'map/');
}


/*$dashboard_messages = array();
foreach($user->getMessages(true) as $message) {
    $from = $message->getFrom();
    $by = $from ? $this->getUserDetailed($from) : 'System';

    $dashboard_messages[] = array('id' => $message->getId(), 'url' => cbg_output::redirect_url($context, 0, 'messages/'), 'global' => $message->isGlobal(), 'by' => $by, 'time' => date(cbg_output::getFormat('datetime'), $message->getTime()), 'summary' => trim(cbg_output::cleanOutput($message->getMessage(), 15)), 'read' => $message->isRead());
}
$this->smarty->assign('gui_dashboard_messages', $dashboard_messages);
//$this->smarty->assign('gui_dashboard_history', $this->getFullHistory($user, 4));
$extra = '';
$time = $user->getLastLogin();
$extra .= '<p>Deine letzte Anmeldung war am '.date($this->getFormat('date'), $time).' um '.date($this->getFormat('time'), $time).' Uhr.</p>';
//$plural = $this->getCount('day', $this->cbg->getServertime()-$user->getRegistrationDate()) != 1 ? 'en' : '';
//$extra .= '<p>Du bist seit '.($this->getCount('day', $this->cbg->getServertime()-$user->getRegistrationDate())).' Tag'.$plural.' Mitglied.</p>';
$extra .= cbg_output::createBlock($context.'users/settings/', 'Einstellungen', 'Deine Einstellungen anzeigen und verändern.', false);
$this->smarty->assign('gui_dashboard_extra', $extra);
if($user->groupOverrideActive() && $user->can('admin_view', true))
    $this->smarty->assign('gui_dashboard_no_settlement', true);*/


?>