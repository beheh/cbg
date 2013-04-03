<p class="unimportant">Support-Anfragen werden direkt an den zuständigen Administrator oder Moderator gesendet.</p>
<form method="post" action=".">
    <table>
        {if $support_error != ""}<tr><td>&nbsp;</td><td class="error">{$support_error}</td></tr>{/if}
        <tr><td>Problemkategorie:</td><td><select name="support_category" selected class="unimportant"><option>Kategorie wählen...</option></select></td></tr>
        <tr><td>Nachricht:</td><td><textarea cols="60" rows="4" name="support_body" placeholder="Problembeschreibung">{$message_body}</textarea></td></tr>
        <tr><td>&nbsp;</td><td><input type="submit" value="Anfrage senden"></td></tr>
    </table>
</form>