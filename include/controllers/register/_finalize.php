<?php
$template_resources_css[] = 'interface_frontend.css';
$template_resources_css[] = 'tipsy.css';
$template_resources_js[] = 'jquery.tipsy.js';
$template_inline_js[] = '$(document).ready(function(){$(\'#form_register_username\').tipsy({gravity:\'w\',trigger:\'focus\'});$(\'#form_register_email\').tipsy({gravity:\'w\',trigger:\'focus\'});$(\'#form_register_key\').tipsy({gravity:\'w\',trigger:\'focus\'});$(\'#form_register_password\').tipsy({gravity:\'w\',trigger:\'focus\'});$(\'#form_register_password_repeat\').tipsy({gravity:\'w\',trigger:\'focus\'});if(!$(\'#form_register_username\').val()){$(\'#form_register_username\').focus();}});';
?>