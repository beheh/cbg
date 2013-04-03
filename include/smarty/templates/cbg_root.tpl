<!doctype html>
<html>
<head>
    <title>{$site_title} &raquo; {$html.title}</title>    
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="description" content="cbg ist ein persistentes, browserbasiertes Spiel.">
    <meta name="author" content="cbg-Team">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="shortcut icon" href="{$root}css/favicon.ico" type="image/x-icon">
    <link rel="icon" href="{$root}css/favicon.ico" type="image/x-icon">
    <link href="http://fonts.googleapis.com/css?family=Convergence" rel="stylesheet" type="text/css">
{foreach from=$resources_css item=css}    <link type="text/css" href="{$root}css/{$css}" rel="stylesheet">
{/foreach}
{foreach from=$resources_js item=js}    <script type="text/javascript" src="{$root}js/{$js}"></script>
{/foreach}
{foreach from=$inline_css item=css}    <style type="text/css">{$css}</style>
{/foreach}
{foreach from=$inline_js item=js}    <script type="text/javascript">{$js}</script>
{/foreach}
</head>
<body lang="de">
{$html.body}
</body>
</html>