{if $form_user_ban_success}
<strong class="success">{$form_user_ban_success}</strong>
{/if}
<form action="." method="post">
<table>
{if ($form_user_ban_unban && $form_user_ban_reason) || !$form_user_ban_unban}
<tr><td>Begründung:</td>
<td>
{if $form_user_ban_unban}
{$form_user_ban_reason}
{else}
<input type="text" name="form_user_ban_reason" placeholder="Nicht erforderlich">{/if}
</td>
</tr>
{/if}
<tr>
<td>Dauer:</td>
<td>
{if $form_user_ban_unban}
{$form_user_ban_until}
{else}
<select name="form_user_ban_duration">
<option value="1">1 Tag</option>
<option value="3">3 Tage</option>
<option value="7" selected>7 Tage</option>
<option value="14">14 Tage</option>
<option value="31">31 Tage</option>
<option value="0">Unbestimmt</option>
</select>
{/if}
</td>
</tr>
<tr><td>&nbsp;</td>
<td>
{if $form_user_ban_unban}
<input type="submit" value="Entbannen" name="form_user_ban_unban_submit">
{else}
<input type="submit" value="Bannen" name="form_user_ban_submit">
{/if}
</td>
</tr>
</table>
</form>
