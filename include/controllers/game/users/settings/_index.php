<?php
$content .= '<h2>Benutzerkonto</h2>';
$form = new cbg_output_form('settings', $context, 'Einstellungen', cbg_output_form::DISPLAY_TABULAR);
$form->email('email', $default, 'E-Mail-Adresse');
$form->password('password', 'Passwort');
$form->password('password_confirm', 'Passwort bestätigen');

$content .= $form->display();


/*if(isset($_POST['form_user_edit_submit'])) {
    $errors = array();
    if(isset($_POST['new_password']) && isset($_POST['new_password_confirm']) && (!empty($_POST['new_password']) || !empty($_POST['new_password_confirm']))) {
        if($_POST['new_password'] !== $_POST['new_password_confirm']) {
            $errors[] = 'Passwörter stimmen nicht überein';
        } else {
            $password = $_POST['new_password'];
            try {
                $user->setPassword($password);
            } catch (Exception $ex) {
                $errors[] = $ex->getMessage();
            }
        }
    }
    try {
        if(isset($_POST['mail'])) {
            $user->setEmail($_POST['mail']);
        }
    } catch (UnexpectedValueException $ex) {
        $errors[] = $ex->getMessage();
    }
    if(isset($errors[0])) {
        $content .= '<strong class="error">'.$this->getIcon('cross').$errors[0].'.</strong>';
    } else {
        $user->save();
        $content .= '<strong class="success">'.$this->getIcon('hook').'Änderungen gespeichert.'.'</strong>';
    }
}
$this->smarty->assign('form_user_edit_mail', $user->getEmail());
$this->smarty->assign('form_user_edit_show_mail', true);
$this->smarty->assign('form_user_edit_admin', false);
$content .= $this->smarty->fetch('interface/form_user_edit.tpl');*/
?>