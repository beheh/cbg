<?php
try {
    $message = new cbg_user_message($this->cbg);
    $message->loadById($params[0]);
    $linkpath[] = array('title' => 'Nachricht #'.$message->getId(), 'link' => $context);
    if((!$message->isGlobal() || $user->can('user_message_global')) && ($message->isGlobal() || $message->getTo()->getId() == $user->getId())) {
        if(isset($_POST['message_remove'])) {
            $message->remove();
            cbg_output::redirect($context, -2, 'removed/');
        } else {
            $content .= '<h2>Nachricht entfernen</h2>';
            $content .= '<form action="." method="post">';
            $content .= '<p>Soll diese Nachricht endgültig gelöscht werden?</p>';
            $content .= '<input type="submit" name="message_remove" value="Entfernen">';
            $content .= '</form>';
        }
    } else {
        $this->displayError('403');
    }
} catch(OutOfBoundsException $ex) {
    $this->displayError('404');
} catch(UnexpectedValueException $ex) {
    cbg_output::redirect($context, -1);
}
?>
