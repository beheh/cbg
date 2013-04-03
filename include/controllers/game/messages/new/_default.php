<?php
$content .= '<h2>Nachricht verfassen</h2>';
$to = isset($_POST['message_to']) ? cbg_output::cleanOutput($_POST['message_to']) : '';
$global = false;
$this->smarty->assign('message_global', $user->can('user_message_global'));
if($user->can('user_message_global')) {
    if(isset($_POST['message_global']) && $_POST['message_global']) {
        $this->smarty->assign('message_global_checked', true);
        $global = true;
    }
}
if(!$global) {
    $this->smarty->assign('message_global_checked', false);
}
$user_id = isset($params[0]) ? $params[0] : false;
try {
    if($user_id || $global) {
        if(!$global) {
            $user_to = $this->cbg->getUserById($user_id, false);
            $this->smarty->assign('message_to', $user_to->getUsername());
        }
        $this->smarty->assign('message_to_disabled', true);
    }
    if((isset($_POST['message_to']) || $user_id || $global) && isset($_POST['message_body'])) {
        $errors = array();
        try {
            if($user_id || $global) {
                if(!$global) {
                    $user_to = $this->cbg->getUserById($user_id, false);
                }
                $this->smarty->assign('message_to_disabled', true);
            } else {
                $user_to = $this->cbg->getUserByName($_POST['message_to'], true);
                $user_id = $user_to->getId();
            }
            if(!$global)
                $this->smarty->assign('message_to', $user_to->getUsername());
        } catch(OutOfBoundsException $ex) {
            $errors[] = 'Benutzer nicht gefunden';
        }
        if(strlen($_POST['message_body']) < $this->cbg->getConfig('user_message_length_min', 10)) {
            $errors[] = 'Nachricht zu kurz (mindestens '.$this->cbg->getConfig('user_message_length_min', 10).' Zeichen)';
        }
        if(strlen($_POST['message_body']) > $this->cbg->getConfig('user_message_length_max', 600)) {
            $errors[] = 'Nachricht zu lang (maximal '.$this->cbg->getConfig('user_message_length_max', 600).' Zeichen)';
        }
        if(!$this->cbg->validString($_POST['message_body'])) {
            $errors[] = 'Nachricht enthält geblockte Wörter';
        }
        if(empty($errors)) {
            try {
                if(!$user_id && !$user->can('user_message_global')) {
                    throw new Exception('cbg_right violation in user_message_global');
                }
                $user->sendMessage($user_id, $_POST['message_body']);
                cbg_output::redirect($context, -1, 'sent/');
                return true;
            } catch(Exception $ex) {
                $content .= '<p><strong class="error">'.$this->getIcon('cross').'Nachricht konnte nicht gesendet werden. Bitte versuche es später erneut.</strong></p>';
                $this->smarty->assign('message_body', cbg_output::cleanOutput($_POST['message_body']));
                $content .= $this->smarty->fetch('interface/form_message.tpl');
            }
        } else {
            $content .= '<p><strong class="error">'.$this->getIcon('cross').$errors[0].'.</strong></p>';
            $this->smarty->assign('message_body', cbg_output::cleanOutput($_POST['message_body']));
            $content .= $this->smarty->fetch('interface/form_message.tpl');
        }
    } else {
        if($user->groupOverrideActive()) {
            $content .= '<p class="pending">Du sendest diese Nachricht als '.$user->getGroup(false, true)->getName().'.</p>';
        }
        $content .= $this->smarty->fetch('interface/form_message.tpl');
    }
} catch(OutOfBoundsException $ex) {
    $this->displayError('404');
}
?>
