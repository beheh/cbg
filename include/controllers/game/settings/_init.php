<?php
$linkpath[] = array('title' => 'Spiel- &amp; Projekteinstellungen', 'link' => $context);
if(!$user->can('project_settings')) {
    $this->displayError('403');
    return false;
}
?>