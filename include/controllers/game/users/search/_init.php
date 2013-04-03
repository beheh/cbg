<?php
if($user->can('admin_view')) {
    $linkpath[] = array('title' => 'Benutzer durchsuchen', 'link' => $context);
} else {
    $linkpath[] = array('title' => 'Spieler suchen', 'link' => $context);
}
?>