<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link type="text/css" href="{$root}css/stylesheet.css" rel="stylesheet">
        <link rel="shortcut icon" href="{$root}css/favicon.ico" type="image/x-icon">
        <link rel="icon" href="{$root}css/favicon.ico" type="image/x-icon">
        <link rel="alternate" type="application/rss+xml" title="RSS" href="{$root}rss/">
        <title>{$site_title}</title>
        <script src="{$root}js/jquery-1.7.1.min.js"></script>
        <script type="text/javascript">
            {literal}window.onload = function() {
                if($('#username').val() == '') {
                    $('#username').focus();
                }
                else {
                    $('#password').focus();
                }
            };{/literal}
            </script>
        </head>
        <body>
            <div class="spacer">&nbsp;</div>
            {nocache}{if $login_failure != ""}
                <div class="infobox"><p class="error">{$login_failure}</p></div>
            {elseif $login_info != ""}
                <div class="infobox"><p class="success">{$login_info}</p></div>
            {else}
                <div class="emptybox">&nbsp;</div>
            {/if}{/nocache}
            <div>
                {if $fatal_error != ""}
                    <table id="content_fatal_error">
                        <tr><td>&nbsp;</td></tr>
                        <tr><td><img src="{$root}css/logo.png" alt="{$project_name}" height="64" width="64"></td></tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr><td>{$fatal_error}</td></tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr><td class="copyright">{$copyright}</td></tr>
                    </table>
                {else}
                    {include file='interface/form_login.tpl'}
                <!--<table id="content_portal">

                </table>!-->
                {/if}
            </div>
        </body>
    </html>