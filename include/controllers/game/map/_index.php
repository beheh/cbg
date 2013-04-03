<?php
$x = 0;
$y = 10;
$template_inline_js[] = 'var initialState = {x:'.$x.',y:'.$y.'}';
$content .= $this->smarty->fetch('interface/game_map.tpl');
?>
