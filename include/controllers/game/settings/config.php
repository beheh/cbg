<?php
$linkpath[] = array('title' => 'Konfiguration', 'link' => $context);
if(isset($_POST['config_form_new_config']) && !empty($_POST['config_form_new_config']) && isset($_POST['config_form_new_value'])) {
    $this->cbg->setConfig($_POST['config_form_new_config'], $_POST['config_form_new_value']);
}
$content .= '<h2>Konfiguration</h2>';
if(isset($_POST['config_form_save'])) {
    $content .= '<strong class="success">' . $this->getIcon('hook') . 'Änderungen gespeichert.</strong>';
}
$content .= '<form action="." method="post">';
$content .= '<table>';
foreach($this->cbg->getConfig() as $config => $value) {
    $changes = '';
    if(isset($_POST['config_form_' . $config]) && $_POST['config_form_' . $config] != $value) {
        $value = $_POST['config_form_' . $config];
        $this->cbg->setConfig($config, $value);
    }
    $content .= '<tr><td><label for="config_form_' . $config . '">' . $config . '</label></td><td><input type="text" value="' . $value . '" id="config_form_' . $config . '" name="config_form_' . $config . '" placeholder="0"></td><td class="unimportant">' . $changes . '</td></tr>';
}
$content .= '<tr><td><input type="text" name="config_form_new_config" placeholder="Neue Variable"></td><td><input type="text" name="config_form_new_value" placeholder="Wert"></td></tr>';
$content .= '<tr><td>&nbsp;</td><td><input type="submit" name="config_form_save" value="Speichern"><input type="reset" value="Zurücksetzen"></td></tr>';
$content .= '</table>';
$content .= '</form>';
?>