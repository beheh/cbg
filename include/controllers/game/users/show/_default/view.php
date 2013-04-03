<?php
//if($user->can('admin_view') || ($user->can('user_edit') || $user->can('user_moderate') || $user->can('user_view'))) {
// Eigener Benutzer?
$same = $cur_user->getId() === $user->getId();
$ban = $cur_user->getBan();
if($cur_user->can('admin_view')) {
    $content .= '<h2>Benutzerinformationen anzeigen</h2>';
} else {
   $content .= '<h2>Spielerinformationen anzeigen</h2>'; 
}
if($same)
    $content .= '<p><strong class="success">'.$this->getIcon('hook').'Das bist du.</strong></p>';
if($ban)
    $content .= '<p><strong class="error">'.$this->getIcon('cross').'Dieser Benutzer ist gebannt.</strong></p>';
$content .= '<table>';
$content .= '<tr><th>Benutzer:</th>'.'<td>'.$this->getUserDetailed($cur_user, false).'</td></tr>';
if($user->can('user_mail_access'))
    $content .= '<tr><th>E-Mail:</th>'.'<td><a href="mailto:'.$cur_user->getEmail().'">'.$cur_user->getEmail().'</a></td></tr>';
$content .= '<tr><th>Clan:</th><td><a href="#">Magical Warriors</a></td></tr>';
$group = $cur_user->getGroup();
if($group && $group->getId() != $this->cbg->getDefaultGroup()) {
    if($user->can('user_edit_all')) {
        $group_str = '<a href="'.cbg_output::redirect_url($context, -1, 'groups/show/'.$group->getId().'/').'">'.$group->getName().'</a>';
    } else {
        $group_str = $group->getName();
    }
    $content .= '<tr><th>Gruppe:</th><td>'.$group_str.'</td></tr>';
}
if(!$cur_user->can('admin_view'))
    $content .= '<tr><th>Rang:</th>'.'<td>#'.$cur_user->getRank().' ('.$cur_user->getPoints().' Punkte)</td></tr>';
if($cur_user->getRegistrationDate() != 0) {
    $invite = $cur_user->getInvitedBy() ? ' (Eingeladen von '.$this->getUserDetailed($cur_user->getInvitedBy()).')' : '';
    $content .= '<tr><th>Registrierung:</th>'.'<td>'.date($this->getFormat('date'), $cur_user->getRegistrationDate()).$invite.'</td></tr>';
}
$badges = array();
//if($cur_user->getRegistrationDate() < mktime(12, 0, 0, 15, 5, 2012)) $badges[] =  $this->getIcon('badge_gold', 'Alpha').'Alpha-Spieler';
//if($cur_user->getRegistrationDate() < mktime(12, 0, 0, 15, 5, 2012)) $badges[] =  $this->getIcon('badge_silver', 'Beta').'Beta-Spieler';
if(count($badges)) {
    $content .= '<tr><th>Abzeichen:</th>'.'<td>';
    $i = 0;
    foreach($badges as $badge) {
        if($i)
            $content .= '<br>';
        $content .= $badge;
        $i++;
    }
    $content .= '</td></tr>';
}
if($ban) {
    if($ban->getUntil()) {
        $until = date(cbg_output::getFormat('date'), $ban->getUntil());
    } else {
        $until = '';
    }
    $by = $ban->getBy() ? ' von '.$this->getUserDetailed($ban->getBy()) : '';
    $reason = cbg_output::parseOutput($ban->getComment(), true);
    $reason = $reason ? ' ('.$reason.')' : '';
    if($until || $reason) {
        $link = $user->can('user_ban') ? '<a href="'.$context.'ban/" class="error">Bis zum '.$until.$reason.'</a>'.$by : '<span class="error">'.$until.$reason.'</span>'.$by;
    } else {
        $link = $user->can('user_ban') ? '<a href="'.$context.'ban/" class="error">Auf unbestimmte Zeit</a>'.$by : '<span class="error">Auf unbestimmte Zeit</span>'.$by;
    }
    $content .= '<tr><th>Gebannt:</th><td>'.$link.'</td></tr>';
} else if($user->can('admin_view')) {
    if($user->can('user_moderate')) {
        $scores = $this->cbg->getAntiCheat(1, $cur_user);
        if($scores) {
            $score = $scores[0];
            if($score['count'] > 7) {
                $content .= '<tr><th>Betrugsversuch:</th><td><a href="'.$contextupper.'moderate/anticheat/'.$cur_user->getId().'/" class="error">Doppelaccount, '.$score['score'].' (aus 10)</a></td></tr>';
            }
        }
    }
}
if($cur_user->isOnline()) {
    $online = '<span class="success"><strong>Online</strong></span>';
} else {
    $dif = $this->cbg->getServertime() - $cur_user->lastOnline();
    if($dif > 12 * 31 * 24 * 60 * 60) {
        $days = 'vor einer Ewigkeit';
    } else if($dif > 31 * 24 * 60 * 60) {
        $monthcount = intval($dif / (31 * 24 * 60 * 60));
        if($monthcount > 1) {
            $days = 'Vor '.$monthcount.' Monaten';
        } else if($monthcount > 0) {
            $days = 'Vor '.$monthcount.' Monat';
        } else if($monthcount == 0) {
            $days = 'Vor unter einem Monat';
        }
    } else if($dif > 7 * 24 * 60 * 60) {
        $weekcount = intval($dif / (7 * 24 * 60 * 60));
        if($weekcount > 1) {
            $days = 'Vor '.$weekcount.' Wochen';
        } else if($weekcount > 0) {
            $days = 'Vor '.$weekcount.' Woche';
        } else if($weekcount == 0) {
            $days = 'Vor unter einer Woche';
        }
    } else {
        $daycount = intval($dif / (24 * 60 * 60));
        $days = '';
        if($daycount > 1) {
            $days = 'Vor '.$daycount.' Tagen';
        } else if($daycount > 0) {
            $days = 'Vor '.$daycount.' Tag';
        } else if($daycount == 0) {
            $days = 'Vor unter einem Tag';
        }
    }
    $class = 'pending';
    if($dif < 24 * 60 * 60)
        $class = 'success';
    $online = '<span class="'.$class.'">'.date(cbg_output::getFormat('date'), $cur_user->lastOnline()).' ('.$days.')</span>';
}
if(!$same)
    $content .= '<tr><th>Zuletzt gesehen:</th><td>'.$online.'</td></tr>';
if(!$same) {
    $content .= '<tr><td>&nbsp;</td>'.'<td><a href="'.ROOT.'game/messages/new/'.$cur_user->getId().'/">Nachricht senden</a> &raquo;</td></tr>';
} else {
    $content .= '<tr><td>Account:</td>'.'<td><a href="'.ROOT.'game/users/settings/">Einstellungen</a> &raquo;</td></tr>';
}
$content .= '</table>';
if($user->can('admin_view')) {
    $content .= cbg_output::createBlock($context.'edit/', 'Benutzer bearbeiten', 'Benutzerdaten bearbeiten und Details anzeigen.', ((!$user->can('user_edit_all') && $cur_user->can('admin_view')) || !$user->can('user_edit')) && $user->getId() != $cur_user->getId());
    if($cur_user->getBan()) {
        $content .= cbg_output::createBlock($context.'ban/', 'Benutzer entbannen', 'Den Benutzer vor Ablauf der Dauer entbannen.', $user->getId() == $cur_user->getId() || $user->getGroupId() == $cur_user->getGroupId() || (!$user->can('user_edit_all') && $cur_user->can('admin_view')));
    } else {
        $content .= cbg_output::createBlock($context.'ban/', 'Benutzer bannen', 'Diesen Benutzer für eine bestimmte Zeit aus dem System verweisen.', $user->getId() == $cur_user->getId() || $user->getGroupId() == $cur_user->getGroupId() || (!$user->can('user_edit_all') && $cur_user->can('admin_view')));
    }
    if($user->can('user_edit_all'))
        $content .= cbg_output::createBlock(cbg_output::redirect_url($context, -2, 'rights/set/'.$cur_user->getGroup()->getId().'/'), 'Berechtigungen testen', 'Die Rechte dieses Benutzers testen.', $same || $user->getGroup()->getId() == $cur_user->getGroup()->getId());
}
//} else {
//    $this->displayError('403');
//}
?>