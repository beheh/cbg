<?php
/*$this->smarty->assign('path', $linkpath);
$nav = array();
if($user->can('admin_view')) {
    $nav[] = array('title' => 'Administration', 'link' => ROOT.'game/');
} else {
    //y$nav[] = array('title' => 'Übersicht', 'link' => ROOT.'game/');
}

if($user->can('admin_view')) {
    $nav[] = array('title' => 'Benutzer/Gruppen', 'link' => ROOT.'game/users/');
    if($user->can('project_settings'))
        $nav[] = array('title' => 'Projekteinstellungen', 'link' => ROOT.'game/settings/');
    $nav[] = array('title' => '');
}
else {
    $nav[] = array('title' => 'Weltkarte', 'link' => ROOT.'game/map/');
    if(!$user->can('admin_view', true)) {
        //$nav[] = array('title' => 'Siedlungen', 'link' => ROOT.'game/settlements/');
    }
    // if ($this->cbg->getCurrentSettlement()) {
    //  $nav[] = array('title' => '');
    //  $nav[] = array('title' => 'Siedlungsübersicht', 'link' => ROOT.'game/settlements/current/');
    //  $nav[] = array('title' => 'Ressourcen', 'link' => ROOT.'game/settlements/current/resources/');
    //  $nav[] = array('title' => 'Gebäude', 'link' => ROOT.'game/settlements/current/buildings/');
    //  $nav[] = array('title' => 'Forschung', 'link' => ROOT.'game/settlements/current/research/');
    //  }
    $nav[] = array('title' => '');
    $nav[] = array('title' => 'Spieler &amp; Statistiken', 'link' => ROOT.'game/users/');
    $nav[] = array('title' => '');
}
$nav[] = array('title' => 'Nachrichten ('.$user->getMessageCount(true).')', 'link' => ROOT.'game/messages/');
$nav[] = array('title' => 'Einstellungen', 'link' => ROOT.'game/users/settings/');
$this->smarty->assign('navigation', $nav);

$info = array();
if($this->cbg->isDebug()) {
    $info[] = 'Debug: true';
    $info[] = 'Querycount: '.$this->cbg->getPDO()->getQueryCount();
    //$info[] = 'Querys_detailed: '.print_r($this->cbg->getPDO()->getQuerys(), true);
    $info[] = 'Servertime: '.$this->cbg->getServertime();
}
if($user->groupOverrideActive()) {
    $info[] = '<a href="'.ROOT.'game/users/override/" title="Du betrachtest diese Seite mit '.$user->getGroup()->getName().'-Rechten">'.$user->getGroup()->getName().'-Rechte</span></a>';
}
if($this->cbg->getActiveMaintenance()) {
    $info[] = '<span class="error">Wartungsmodus</span>';
}
$string = '';
$count = 0;
foreach($info as $txt) {
    if($count)
        $string .= ' &laquo; ';
    $string .= $txt;
    $count++;
}
$this->smarty->assign('main_info', $string);*/

$template_resources_css[] = 'interface_game.css';


//$content .= $this->smarty->fetch('cbg_interface_game.tpl');

$sidebar = '';
//<p><a href="{$root}game/"><img src="{$root}css/logo.png" alt="{$project_name}" height="80" width="80" title="cbg."></a></p>
$sidebar .= cbg_output::html_element('div', cbg_output::link(ROOT.'game/', cbg_output::css_image('logo.png', 'cbg', 80, 80, array('title' => 'cbg.'))), array('id' => 'logo', 'class' => 'box'));

$navigation = '';
//<p>{$user.name}  <a href="{$root}logout/" class="logout">Abmelden</a></p>
$navigation .= cbg_output::html_element('p', $this->getUserDetailed($cbg->getCurrentUser()).' &raquo; '.cbg_output::link(ROOT.'logout/', 'Abmelden'));
$sidebar .= cbg_output::html_element('nav', $navigation, array('id' => 'menu', 'class' => 'box'));

$template_body .= cbg_output::html_element('div', $sidebar, array('id' => 'sidebar'));

$template_body .= cbg_output::html_element('div', $content, array('id' => 'content', 'class' => 'box'));
?>