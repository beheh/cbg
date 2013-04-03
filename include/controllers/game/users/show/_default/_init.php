<?php
$user_id = $params[0];
if(!empty($user_id)) {
    try {
        $cur_user = $this->cbg->getUserById($user_id, false);
        $linkpath[] = array('title' => $cur_user->getUsername(), 'link' => $context);
    } catch(OutOfBoundsException $e) {
        $this->displayError('404');
        return false;
    }
} else {
    cbg_output::redirect($context, -1);
}
?>
