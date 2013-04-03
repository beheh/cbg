<?php
function smarty_function_input($params, Smarty_Internal_Template $template) {
    $defaults = array(
        'type' => 'text',
        'option_label' => false,
        'option_as_table' => false,
        'option_two_cell' => true,
    );
    $params = array_merge($defaults, $params);
    $form_id = $template->getTemplateVars('form_id');
    switch($params['type']) {
        case 'textarea':
            $output .= '<textarea name="'.$params['name'].'">';
            
    }
}
?>