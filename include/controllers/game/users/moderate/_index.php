<?php
$content .= '<h2>Benutzer moderieren</h2>';
$content .= cbg_output::createBlock($context.'bans/', 'Gebannte Benutzer anzeigen', 'Alle gebannten Benutzer anzeigen und Bans verwalten.', !$user->can('user_ban'));
$content .= cbg_output::createBlock($context.'anticheat/', 'Betrugsverdachte anzeigen', 'Vermutete Betrugsverdachte und Heuristiken anzeigen.', !$user->can('user_moderate'));
?>
