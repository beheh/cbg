<?php

$smarty->assign('resources_css', $template_resources_css);
$smarty->assign('resources_js', $template_resources_js);
$smarty->assign('inline_css', $template_inline_css);
$smarty->assign('inline_js', $template_inline_js);
$smarty->assign('html', array('title'=> $template_title, 'body' => $template_body));

$output .= $smarty->fetch('cbg_root.tpl');
?>