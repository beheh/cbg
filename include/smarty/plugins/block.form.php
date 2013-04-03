<?php
function smarty_block_form($params, $content, Smarty_Internal_Template $template, $repeat) {
    $defaults = array(
        'method' => 'POST',
    );
    $params = array_merge($defaults, $params);
    if(!$repeat) {
        // Ausgabe
        $output = '<form method="'.$params['method'].'" action="'.$params['action'].'">';
        $output .= $content;
        $output .= '</form>';
        return $output;
    } else {
        $template->assign('form_id', $params['id']);
    }
}
?>