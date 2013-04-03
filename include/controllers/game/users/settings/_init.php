<?php
$linkpath[] = array('title' => 'Einstellungen', 'link' => $context);
if($user->can('user_edit_all')) {
    cbg_output::redirect($context, -1, 'show/'.$user->getId().'/edit/');
}
?>