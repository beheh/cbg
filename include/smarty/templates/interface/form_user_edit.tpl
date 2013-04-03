<form action="." method="post">
<table>
{if $form_user_edit_admin == true}<tr><td>Benutzername:</td><td><input type="text" name="username" value="{$form_user_edit_name}"></td></tr>{/if}
{if $form_user_edit_show_mail != null}<tr><td>E-Mail-Adresse:</td><td><input type="email" name="mail" value="{$form_user_edit_mail}"></td></tr>{/if}
<tr><td>Neues Passwort:</td><td><input  name="new_password" type="password"></td></tr>
<tr><td>Passwort bestätigen:</td><td><input name="new_password_confirm" type="password"></td></tr>
{if $form_user_edit_admin == true}
{if $form_user_edit_groups != null}
<tr><td>Gruppe:</td><td><select name="group">{section name=i loop=$form_user_edit_groups}<option value="{$form_user_edit_groups[i].id}"{if $form_user_edit_groups[i].selected} selected{/if}>{$form_user_edit_groups[i].name}</option>{/section}</select>
{if $form_user_edit_warn_group}<p class="pending"><strong>Gruppenänderungen können möglicherweise nicht mehr rückgängig gemacht werden.</strong></p>{/if}
</td></tr>
{/if}
{if $form_user_edit_show_invites}
<tr><td>Einladungen:</td><td><input name="invites" type="number" value="{$form_user_edit_invites}" min="0"></td></tr>
{/if}
{/if}
<tr><td>Account löschen:</td><td>Bestätigen <input type="checkbox" name="remove_user"></td></tr>
<tr><td>&nbsp;</td><td><input type="submit" name="form_user_edit_submit" value="Speichern"><input type="reset" value="Zurücksetzen"></td></tr>
</table>
</form>
