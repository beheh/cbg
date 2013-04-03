<?php
if(!isset($params[0]) || $params[0] == 'sent') {
    if (isset($params[0]))
        $context = cbg_output::redirect_url($context, -1);
    $maintenances = $this->cbg->getNextMaintenance();
    if($maintenances) {
        $content .= '<h2>Ankündigungen</h2>';
        $current = $this->cbg->getActiveMaintenance();
        $id = $current ? $current['id'] : 0;
        foreach($maintenances as $row) {
            $active = $id == $row['id'] ? ' &raquo; <span class="error">Aktiv</span>' : '';
            $content .= '<p class="mblock"><strong>Geplante Wartung am '.date($this->getFormat('date'), $row['from']).$active.'</strong><br>'.$row['reason'].' - Vorraussichtliche Uhrzeit: '.date($this->getFormat('time'), $row['from']).'-'.date($this->getFormat('time'), $row['until']);
        }
    }
    $content .= '<h2>Private Nachrichten</h2>';
    print_r($_GET);
    if(isset($_GET['removed'])) {
        $content .= '<p><strong class="success">'.$this->getIcon('hook').'Nachricht entfernt.</strong></p>';
    }
    if(isset($_GET['sent'])) {
        $content .= '<p><strong class="success">'.$this->getIcon('hook').'Nachricht gesendet.</strong></p>';
    }
    $content .= '<ol class="messages">';
    $count = 0;
    foreach($user->getMessages(true) as $message) {
        $from = $message->getFrom() ? $this->getUserDetailed($message->getFrom()) : 'System';
        if(isset($params[0]) && $params[0] == $message->getId()) {
            $content .= '<li class="bghighlight">';
        } else {
            $content .= '<li>';
        }
        $extra = '';
        $extra .= cbg_output::createIconLink('cross', $context.'remove/'.$message->getId().'/', 'Entfernen', $message->isGlobal() && !$user->can('user_message_global'));
        $extra .= $message->isGlobal() ? 'Globale Nachricht' : '';
        $content .= '<p class="mblock"><strong><a name="m'.$message->getId().'"></a>'.date(cbg_output::getFormat('datetime'), $message->getTime()).' &raquo; '.$from.' &raquo; '.$extra.'</strong><br>'.$this->parseOutput($message->getMessage()).'</p>';
        if(!$message->isGlobal() && !$message->isRead())
            $message->read();
        $count++;
    }
    if(!$count) {
        $content .= '<li class="disabled">Keine Nachrichten vorhanden. Empfangene Nachrichten von anderen Benutzern werden hier angezeigt.</li>';
    }
    $content .= '</ol>';
    $content .= '<a href="'.cbg_output::redirect_url($context, 0, 'new/').'">Nachricht verfassen</a> &raquo;';
} else {
    $this->displayError('404');
}
?>
