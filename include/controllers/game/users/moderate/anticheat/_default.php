<?php
$linkpath[] = array('title' => 'Betrugsverdachte', 'link' => $context);
if(isset($params[0])) {
    try {
        $user_id = $params[0];
        if(is_numeric($user_id)) {
            $cur_user = $this->cbg->getUserById($user_id, false);
            $scores = $this->cbg->getAntiCheat(1, $cur_user);
            if(!isset($scores[0]))
                throw new OutOfBoundsException('unknown anticheat: user '.$cur_user->getId());
            $score = $scores[0];
            $link = isset($path[4]) ? $path[4] : 'view';
            $linkpath[] = array('title' => $cur_user->getUsername(), 'link' => $context);
            if(($score['player1']->getGroup()->getId() == $this->cbg->getDefaultGroup() && $score['player2']->getGroup()->getId() == $this->cbg->getDefaultGroup()) || $user->can('user_edit_all')) {
                $content .= '<h2>Betrugsverdacht anzeigen</h2>';
                $content .= '<table>';
                $content .= '<tr><th>Betrugsart:</th><td>Doppelaccount</td></tr>';
                $content .= '<tr><th>Benutzer 1:</th><td>'.$this->getUserDetailed($score['player1']).'</td></tr>';
                $content .= '<tr><th>Benutzer 2:</th><td>'.$this->getUserDetailed($score['player2']).'</td></tr>';
                $content .= '<tr><th>Punkte:</th><td><span class="error">'.$score['score'].' (aus 10)</span></td></tr>';
                $days = 'an '.$score['count'].' Tagen';
                if($score['count'] == 1)
                    $days = 'an '.$score['count'].' Tag';
                $class = $score['count'] < 3 ? 'pending' : 'error';
                $content .= '<tr><th>Anzahl:</th><td><span class="'.$class.'">'.$days.'</span></td></tr>';
                $content .= '<tr><th>Zuletzt:</th><td>'.date($this->getFormat('datetime'), $score['time']).'</td></tr>';
                $content .= '</table>';
                //$content .= cbg_output::createBlock($context, 'Benutzer verwarnen', 'Noch nicht implementiert.', $user->can('user_ban'));
                $content .= cbg_output::createBlock(ROOT.'game/users/show/'.$score['player1']->getId().'/ban/', $this->getUserDetailed($score['player1'], true).' bannen', 'Den Benutzer aus dem System verweisen.', !$user->can('user_ban') || $score['player1']->getBan() || $user->getId() == $score['player1']->getId());
                $content .= cbg_output::createBlock(ROOT.'game/users/show/'.$score['player2']->getId().'/ban/', $this->getUserDetailed($score['player2'], true).' bannen', 'Den Benutzer aus dem System verweisen.', !$user->can('user_ban') || $score['player2']->getBan() || $user->getId() == $score['player2']->getId());
            }
            else {
                $this->displayError('403');
                return true;
            }
        } else {
            cbg_output::redirect($context, 0);
            return true;
        }
    } catch (OutOfBoundsException $ex) {
        $this->displayError('404');
        return true;
    }
} else {
    $scores = $this->cbg->getAntiCheat();
    /* $content .= '<h2>Betrugsversuche</h2>';
      $count = 0; */
    foreach($scores as $key => $score) {
        if(($score['player1']->can('admin_view') || $score['player2']->can('admin_view')) && !$user->can('user_edit_all')) {
            $scores[$key] = null;
        }
        /*   if ($score['count'] <= 3)
          continue;
          if ($score['score'] <= 5)
          continue;
          if (!$scores[$key])
          continue;
          $content .= cbg_output::createBlock($context.$score['player1']->getId().'/', 'Doppelaccount - '.$score['score'].' Punkte', $this->getUserDetailed($score['player1'], true).' - '.$this->getUserDetailed($score['player2'], true));
          $scores[$key] = null;
          $count++; */
    }
    /* if (!$count) {
      $content .= '<p class="unimportant">Es liegen keine Betrugsversuche vor.</p>';
      } */
    $content .= '<h2>Betrugsverdachte</h2>';
    $count = 0;
    foreach($scores as $score) {
        if(!$score)
            continue;
        $content .= cbg_output::createBlock($context.$score['player1']->getId().'/', 'Doppelaccount - '.$score['score'].' Punkte', $this->getUserDetailed($score['player1'], true).' - '.$this->getUserDetailed($score['player2'], true));
        $count++;
    }
    if(!$count) {
        $content .= '<p class="unimportant">Es liegen keine Betrugsverdachte vor.</p>';
    }
}
?>