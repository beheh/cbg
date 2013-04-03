<?php
if($user->can('admin_view')) {
    $linkpath[] = array('title' => 'Benutzer', 'link' => $context);
} else {
    $linkpath[] = array('title' => 'Spieler', 'link' => $context);
}
?>