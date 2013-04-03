<?php
$linkpath[] = array('title' => 'Statistiken', 'link' => $context);
$content .= '<h2>Live-Daten</h2>';
$content .= '<table>';
$content .= '<tr><td>Benutzer online</td><td>' . $this->cbg->getUsersOnlineCount() . '</td></tr>';
$content .= '</table>';
$content .= '<h2>Statistiken</h2>';
?>