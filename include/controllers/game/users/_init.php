<?php
if($user->can('admin_view')) {
    $linkpath[] = array('title' => 'Benutzer und Gruppen', 'link' => $context);
} else {
    $linkpath[] = array('title' => 'Spieler und Statistiken', 'link' => $context);
}
?>