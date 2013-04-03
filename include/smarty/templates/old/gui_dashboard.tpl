<table id="overview">
    <tr>
        <td class="narrow">
            <h2><a href="{$root}game/messages/">Private Nachrichten</a></h2>
            <ol>
                {if $gui_dashboard_messages ne null}
                    {section name=i loop=$gui_dashboard_messages}
                        <li>
                            <a href="{$gui_dashboard_messages[i].url}">{if !$gui_dashboard_messages[i].read || $gui_dashboard_messages[i].global}<strong>{/if}{$gui_dashboard_messages[i].time}{if !$gui_dashboard_messages[i].read || $gui_dashboard_messages[i].global}</strong>{/if}</a> &laquo; {$gui_dashboard_messages[i].by}
                        </li>
                    {/section}
                {else}
                    <li><span class="disabled">Keine Nachrichten vorhanden.</span></li>
                {/if}
            </ol>
            <a href="{$root}game/messages/new/">Nachricht verfassen</a> &raquo;
        </td>
        {if !$gui_dashboard_administration}
            <td class="narrow">
                <h2><a href="{$root}game/settlements/">Siedlungen</a></h2>
                {if $gui_dashboard_no_settlement != true}
                    {$gui_dashboard_history}
                    <a href="{$root}game/settlements/history/">Verlauf anzeigen</a> &raquo;
                {else}
                    <p class="unimportant">Siedlungsübersicht nicht möglich.</p>
                {/if}
            </td>
            <td class="narrow" style="padding-right: 0px;">
                <h2><a href="{$root}game/users/statistics/">Statistiken</a></h2>
                <ol style="">
                    <li>1. {$user.name} (100132)</li>
                    <li>2. {$user.name} (100132)</li>
                    <li>3. {$user.name} (100132)</li>
                    <li>4. {$user.name} (100132)</li>
                    <li>5. {$user.name} (100132)</li>
                </ol>
                <a href="{$root}game/users/statistics/">Statistiken anzeigen</a> &raquo;
            </td>
        {else}
            <td class="wide" style="padding-right: 0px;">
                <h2><a href="{$root}game/">Administration</a></h2>
                {$gui_dashboard_administration}
            </td>
        {/if}
    </tr>
    {if $gui_dashboard_extra}
        <tr>
            <td colspan="2"><h2><a href="{$user.profile}">Account</a></h2>
        {$gui_dashboard_extra}
        </td></tr>
{/if}
</table>