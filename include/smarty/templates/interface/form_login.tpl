<form method="post" action="{$root}login/">
    <table id="content_login">
        <tr><td>&nbsp;</td></tr>
        <tr><td><img src="{$root}css/logo.png" alt="{$project_name}" width="80" height="80"></td></tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td><label for="username">Benutzername</label></td></tr>
        <tr><td><input type="text" name="username" id="username" style="width: 145px;"{if $login_value_user ne ""} value="{$login_value_user}" {/if}></td></tr>
        <tr><td><label for="password">Passwort</label></td></tr>
        <tr><td><input type="password" name="password" id="password" value="" style="width: 145px;"></td></tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td><input type="submit" value="Anmelden" style="width: 150px;"></td></tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td><a href="{$root}register/">Benutzerkonto anlegen</a> &raquo;</td></tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td class="copyright">{$copyright}</td></tr>
    </table>
</form>
