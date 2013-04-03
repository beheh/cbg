<?php
$content .= '<h2>Spieleinstellungen</h2>';
$content .= '<h2>Projekteinstellungen</h2>';
$content .= cbg_output::createBlock(cbg_output::redirect_url($context, 0, 'statistics/'), 'Statistiken', 'Statistiken des Projekts anzeigen und zurücksetzen.', false);
$content .= cbg_output::createBlock(cbg_output::redirect_url($context, 0, 'config/'), 'Projektkonfiguration', 'Projektvariablen anzeigen und modifizieren.', false);
$content .= cbg_output::createBlock(cbg_output::redirect_url($context, 0, 'maintenance/'), 'Projektwartung', 'Projektwartungen planen und ausführen.', false);
$content .= cbg_output::createBlock(cbg_output::redirect_url($context, 0, 'api/'), 'Kommandozeile', 'Auf die Projekt-API zugreifen und Befehle ausführen.', false);
?>