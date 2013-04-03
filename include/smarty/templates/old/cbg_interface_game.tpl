<div id="sidebar">
    <div id="logo_box">
        <p><a href="{$root}game/"><img src="{$root}css/logo.png" alt="{$project_name}" height="80" width="80" title="cbg."></a></p>
    </div>
    <nav id="navigation_box">
        <p>{$user.name} &raquo; <a href="{$root}logout/" class="logout">Abmelden</a></p>
        <ul>{section name=i loop=$navigation}<li>{if $navigation[i].title != ''}<a href="{$navigation[i].link}">{$navigation[i].title}</a>{else}&nbsp;{/if}</li>{/section}</ul>
        <p class="navinfo"><span id="servertime">{$servertime}</span> &laquo; {$serverdate}<br>{$project_name} v{$project_version}</p>
    </nav>
</div>
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
</h1>!-->
{$content}
<footer class="content_copyright">{if $main_info}{$main_info} &laquo; {/if}{$copyright}</footer>
</div>