<?php
$login_failure = false;
if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['password'])) {
    $user = null;
    try {
        $user = $this->cbg->getUserByName($_POST['username'], false);
        if($user->matchPassword($_POST['password']) && (!$maintenance || $user->can('project_maintenance'))) {
            $user->setActive();
            /* $ban = $user->getBan();
              if($ban) {
              $by = $ban->getBy();
              $content = '<p><span class="error">Dein Benutzerkonto wurde';
              if($by) {
              $content .= ' von '.$by->getUsername();
              }
              if($ban->getUntil()) {
              $content .= ' bis zum '.date(cb1g_output::getFormat('date'), $ban->getUntil()).' um '.date(cbg_output::getFormat('time'), $ban->getUntil()).' Uhr';
              } else {
              $content .= ' auf unbestimmte Zeit';
              }
              $content .= ' gebannt.';
              if($ban->getComment() != '') {
              $content .= ' Grund: '.$this->parseOutput($ban->getComment()).'.';
              }
              $content .= '</span></p>';
              $content .= '<p><a href="'.ROOT.'" class="important">Zurück zur Startseite</a></p>';
              $this->smarty->assign('error', $content);
              $this->smarty->display('cbg_error.tpl');
              } else { */
            $this->cbg->startSession($user);
            header('Location: '.ROOT.'game/');
            //}
            return true;
        } else {
            $login_failure = 'Passwort falsch.';
        }
    } catch(OutOfBoundsException $ex) {
        $login_failure = 'Benutzername unbekannt.';
    }
}

$form = new cbg_output_form('login', cbg_output::url('login/'), 'Login', cbg_output_form::DISPLAY_TABULAR, array('table-class' => 'box'));
$form->set_option('tabular_two_row', true);
$form->new_line();
$form->raw_html(cbg_output::link(ROOT, cbg_output::css_image('logo.png', 'cbg', 80, 80)), array('alt' => $this->cbg->getProjectName()));
$form->new_line();
$username = '';
if(isset($_POST['username']) && !empty($_POST['username'])) {
    $username = cbg_output::cleanOutput($_POST['username']);
}
$form->text('username', $username, 'Benutzername', array('autofocus' => 'autofocus', 'required' => 'required'));
$form->password('password', 'Passwort', array('required' => 'required'));
$form->new_line();
$form->submit('Anmelden');
$form->new_line();
$form->raw_html(cbg_output::link(cbg_output::url('register/'), 'Registrieren').' &raquo;');
$form->new_line();
$form->raw_html(cbg_output::html_element('footer', '&copy; 2012 '.cbg_output::link(ROOT, 'cbg')));

$class = '';
$text = '';
if($login_failure) {
    $class .= 'box error';
    $text = $login_failure;
} else if(isset($_GET['session'])) {
    $class .= 'box error';
    $text = 'Anmeldung nicht (mehr) gültig.';
} else if(isset($_GET['done'])) {
    $class .= 'box success';
    $text = 'Abmeldung erfolgreich!';
}

$introduction = '';
$introduction .= '<h1>Willkommen bei cbg.</h1>';
$introduction .= '<p>'.cbg_output::link(cbg_output::url(),'cbg').' bringt die spannende Welt von <a href="http://www.clonk.de/">Clonk</a> - einem zweidimensionalen Jump\'n\'Run-Spiel - in eine Echtzeit-Strategieumgebung.</p>';
$introduction .= '<p>Errichte und erobere Siedlungen und Burgen, durchkämme die Welt nach wertvollen Bodenschätzen und Ressourcen, handle mit deinen Mitspielern und führe verschiedenste Missionen aus.</p>';
$introduction .= '<p>'.cbg_output::link(cbg_output::url('register/'), '<strong>Registriere dich jetzt</strong>').' &raquo;</p>';
$center .= cbg_output::html_element('div', $introduction, array('class' => 'box white', 'id' => 'introduction')).PHP_EOL;
$center .= cbg_output::html_element('div', $text, array('class' => $class, 'id' => 'notice')).PHP_EOL;
$center .= $form->display();

$template_body .= cbg_output::html_element('div', $center, array('id' => 'center')).PHP_EOL;
?>