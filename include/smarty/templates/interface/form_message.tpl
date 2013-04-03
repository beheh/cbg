<form method="post" action=".">
    <table>
        <tr><td>Empfänger:</td><td><input type="text" name="message_to"{if $message_to != false} value="{$message_to}"{/if}{if $message_to_disabled}{if $message_to == false} value="Kein Empfänger"{/if} disabled{else} placeholder="Spielername"{/if}>{if !$message_to_disabled}<span class="spaced">(Benutzername eingeben)</span>{/if}</td></tr>
{if $message_global}        <tr><td>&nbsp;</td><td><input type="checkbox" id="message_global" name="message_global"{if $message_global_checked} checked{/if}><label for="message_global">Globale Nachricht</label></td></tr>{/if}
        <tr><td>Nachricht:</td><td><textarea cols="60" rows="4" name="message_body" placeholder="Nachrichtentext">{$message_body}</textarea></td></tr>
        <tr><td>&nbsp;</td><td><input type="submit" value="Nachricht senden"></td></tr>
    </table>
</form>