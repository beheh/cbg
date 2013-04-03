<!DOCTYPE html>
<html>
    <head>
        <title>{$site_title}{if $path} {section name=i loop=$path}&raquo; {$path[i].title} {/section}{/if}</title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8">
        <link type="text/css" href="{$root}css/stylesheet.css" rel="stylesheet">
        <link type="text/css" href="{$root}css/tipsy.css" rel="stylesheet">
        <link rel="shortcut icon" href="{$root}css/favicon.ico" type="image/x-icon">
        <link rel="icon" href="{$root}css/favicon.ico" type="image/x-icon">
        <script src="{$root}js/jquery-1.7.1.min.js"></script>
        <script src="{$root}js/jquery.tipsy.js"></script>
        <script>
            var ROOT = '{$root}';           
        </script>
        <script>
            var h, m, s;
            $(document).ready(function() {
                var full = $('#servertime').text().split(':');
                h = parseInt(unpad(full[0])); m = parseInt(unpad(full[1])); s = parseInt(unpad(full[2]));
                if(h >= 24) return;
                setTimeout('tick()', 1000-{$servermilli});
            });

            function pad(number) {
                return (number < 10 ? '0' : '') + number;
            }
            
            function unpad(string) {
                if(string[0] == '0') {
                  string = string.substr(1);
            }
                return string;
            }

            function refresh() {
                location.reload();
            }

            function tick() {
                s++;
                if(s >= 60) { m++; s = 0; }
                if(m >= 60) { h++; m = 0; }
                if(h >= 24) { h = 0; location.reload(); }
                $('#servertime').text(pad(h)+':'+pad(m)+':'+pad(s));
                setTimeout('tick()', 1000);
            }
        </script>
    </head>
    <body>
        <!--<div id="sidebar">
            <div id="logo_box">
                <p><a href="{$root}game/"><img src="{$root}css/logo.png" alt="{$project_name}" height="80" width="80" title="cbg."></a></p>
            </div>
            <nav id="navigation_box">
                <p>{$user.name} &raquo; <a href="{$root}logout/" class="logout">Abmelden</a></p>
                <ul>{section name=i loop=$navigation}<li>{if $navigation[i].title != ''}<a href="{$navigation[i].link}">{$navigation[i].title}</a>{else}&nbsp;{/if}</li>{/section}</ul>
                <p class="navinfo"><span id="servertime">{$servertime}</span> &laquo; {$serverdate}<br>{$project_name} v{$project_version}</p>
            </nav>
        </div>!-->
        <div id="content_main">
            <h1>
            {section name=i loop=$path}&raquo; <a href="{$path[i].link}">{$path[i].title}</a> {/section}
            {if $user.admin != true}
                {if $user.settlements}
                    <form action="." method="post">
                        <noscript>
                        <input type="submit" value="Auswählen" style="float: right;">
                        </noscript>
                        <select style="float: right;" name="game_settlement_select" onchange="this.form.submit()">
                            {section name=i loop=$user.settlements}
                                <option value="{$user.settlements[i].id}"{if $user.settlements[i].current == true} selected{/if}>{$user.settlements[i].name}</option>
                            {/section}
                        </select>
                    </form>
                {else}
                    <select style="float: right;" disabled>
                        <option>Keine Siedlung</option>
                    </select>
                {/if}
            {/if}
        </h1>
        {$content}
        <footer class="content_copyright">{if $main_info}{$main_info} &laquo; {/if}{$copyright}</footer>
    </div>
</body>
</html>
