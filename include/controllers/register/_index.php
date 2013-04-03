<?php
/* $errors = array();
  $registered = false;
  if (isset($_POST['username']) && isset($_POST['email']) && (isset($_POST['key']) || isset($path[1]) || $this->cbg->getOpenRegistration()) && isset($_POST['password']) && isset($_POST['password2'])) {
  $this->smarty->assign('register_value_user', cbg_output::cleanOutput($_POST['username']));
  if (!$this->cbg->validUsername($_POST['username']) || empty($_POST['username'])) {
  $errors[] = 'Benutzername ungültig.';
  $this->smarty->assign('register_user_error', true);
  } else {
  $error = false;
  try {
  $user = $this->cbg->getUserByName($_POST['username'], true);
  if ($user)
  $error = true;
  } catch (OutOfBoundsException $ex) {

  }
  if ($error) {
  $errors[] = 'Benutzername bereits vergeben.';
  $this->smarty->assign('register_user_error', true);
  }
  }
  $this->smarty->assign('register_value_email', cbg_output::cleanOutput($_POST['email']));
  if (!$this->cbg->validEmail($_POST['email']) || empty($_POST['username'])) {
  $errors[] = 'E-Mail-Adresse ungültig.';
  $this->smarty->assign('register_email_error', true);
  }
  if (!$this->cbg->getOpenRegistration() || isset($path[1])) {
  $key = isset($_POST['key']) ? trim($_POST['key']) : '';
  $key = isset($path[1]) && $path[1] != 'showkey' ? $path[1] : $key;
  if ($key != 'showkey')
  $this->smarty->assign('register_value_key', cbg_output::cleanOutput($key));
  if (!$this->cbg->verifyKey($key) && (!empty($key) || !$this->cbg->getOpenRegistration())) {
  if (!$this->cbg->findKey($key)) {
  $errors[] = $this->cbg->getOpenRegistration() ? 'Referenzschlüssel ungültig' : 'Betaschlüssel ungültig.';
  } else {
  $errors[] = $this->cbg->getOpenRegistration() ? 'Referenzschlüssel bereits vergeben' : 'Betaschlüssel bereits vergeben.';
  }
  $this->smarty->assign('register_key_error', true);
  }
  }
  if ($_POST['password'] != $_POST['password2'] && !empty($_POST['password'])) {
  $errors[] = 'Passwörter stimmen nicht überein.';
  $this->smarty->assign('register_password_error', true);
  } else if (!$this->cbg->validPassword($_POST['password'])) {
  $errors[] = 'Passwort ungültig.';
  $this->smarty->assign('register_password_error', true);
  }
  if (empty($errors)) {
  $key = isset($_POST['key']) ? trim($_POST['key']) : '';
  $key = isset($path[1]) && $path[1] != 'showkey' ? $path[1] : $key;
  if (($this->cbg->getOpenRegistration() && empty($key)) || $this->cbg->useKey($key)) {
  try {
  $user = $this->cbg->newUser($_POST['username'], $_POST['password'], $_POST['email'], $this->cbg->getKeyGroup($key), $this->cbg->getKeyOwner($key));
  } catch (OutOfBoundsException $ex) {

  }
  if ($user) {// && $user->sendActivationMail()) {
  $user->save();
  $this->smarty->assign('register_text', '<p class="success">Deine Registrierung war erfolgreich.</p>');
  $registered = true;
  } else {
  $error = true;
  }
  } else {
  $error = true;
  }
  if ($error) {
  $this->smarty->assign('register_text', '<p class="error">Bei deiner Registrierung ist ein Fehler aufgetreten. Bitte versuche es später erneut.</p>');
  }
  } else {
  $this->smarty->assign('register_failure', $errors[0]);
  }
  }
  if (isset($path[1]) && !$registered) {
  $key = $path[1];
  if ($key == 'showkey') {
  $this->smarty->assign('register_key_show', true);
  } else {
  if ($this->cbg->findKey($key)) {
  if ($this->cbg->verifyKey($key)) {
  $this->smarty->assign('register_value_key', $key);
  $this->smarty->assign('register_key_disabled', true);
  } else {
  $this->displayError('data');
  return true;
  }
  } else {
  $this->displayError('404');
  return true;
  }
  }
  }
  if ($this->cbg->getOpenRegistration())
  $this->smarty->assign('open_registration', true);
  $this->smarty->assign('site_title', $this->cbg->getProjectName().' '.$this->seperator.' Registrierung');
  $output = $this->smarty->fetch('cbg_register.tpl'); */

$form = new cbg_output_form('register', cbg_output::url('register/'), 'Registrieren', cbg_output_form::DISPLAY_TABULAR, array('table-class' => 'box'));
$form->set_option('tabular_two_row', true);
$form->set_option('highlight_required', true);
$form->new_line();
$form->raw_html(cbg_output::link(ROOT, cbg_output::css_image('logo.png', 'cbg', 80, 80)), array('alt' => $this->cbg->getProjectName()));
$form->new_line();
$username = '';
if(isset($_POST['username']) && !empty($_POST['username'])) {
    $username = cbg_output::cleanOutput($_POST['username']);
}
$form->text('username', $username, 'Benutzername', array('required' => 'required', 'autofocus' => 'autofocus', 'original-title' => '(Erforderlich) Der Benutzername muss zwischen 3 und 12 Zeichen lang sein und darf Buchstaben, Zahlen und einige Sonderzeichen enthalten.'));
$form->email('email', '', 'E-Mail-Adresse', array('required' => 'required', 'original-title' => '(Erforderlich) Es muss eine gültige E-Mail-Adresse zur Aktivierung des Kontos angegeben werden.'));
$form->text('key', '', 'Betaschlüssel', array('required' => 'required', 'original-title' => '(Erforderlich) Ein Betaschlüssel ist von Administratoren und einigen Spielern erhältlich und zur Registrierung erforderlich.'));
$form->password('password', 'Passwort', array('required' => 'required', 'original-title' => '(Erforderlich) Das Passwort kann nach der Anmeldung geändert werden. Groß-/Kleinschreibung wird unterschieden.'));
$form->password('password_repeat', 'Passwort wiederholen', array('required' => 'required', 'original-title' => '(Erforderlich) Eine erneute Eingabe des Passworts wird benötigt, um Tippfehler zu vermeiden.'));
$form->new_line();
$form->submit('Registrieren');
$form->new_line();
$form->raw_html('&laquo; '.cbg_output::link(cbg_output::url(), 'Zurück zur Anmeldung'));
$form->new_line();
$form->raw_html(cbg_output::html_element('footer', $this->getCopyright()));

$template_body .= cbg_output::html_element('div', '', array('id' => 'notice')).PHP_EOL;

$template_body .= cbg_output::html_element('div', $form->display(), array('id' => 'register')).PHP_EOL;
?>