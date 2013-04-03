{if $form_invite_keys || ($form_invite_admin && !$form_invite_open_registration)}
{if $form_invite_admin != true}
{if $form_invite_invites_left > 0}
<p>Du kannst noch <strong>{$form_invite_invites_left}</strong> Freund{if $form_invite_invites_left > 1}e{/if} einladen:</p>
{else}
<p>Du hast keine {if $form_invite_keys != false}weiteren {/if}Einladungen verfügbar.</p>
{/if}
{else}
{if !$form_invite_open_registration || $form_invite_admin_all}
<p>Neue Einladungen generieren:</p>
<form action="." method="post">
<select name="form_invite_request">
<option value="1">1 Einladung</option>
<option value="2">2 Einladungen</option>
<option value="3">3 Einladungen</option>
<option value="5">5 Einladungen</option>
<option value="10">10 Einladungen</option>
</select>
{if $form_invite_admin_all}
<select name="form_invite_group">
{section name=i loop=$form_invite_groups}<option value="{$form_invite_groups[i].id}"{if $form_invite_groups[i].selected} selected{/if}>{$form_invite_groups[i].name}</option>{/section}
</select>
{/if}
<input type="submit" value="Generieren">
</form>
{/if}
{if $form_invite_invites_left > 0}
<p>Deine Registrierungsschlüssel:</p>
{/if}
{/if}
{if $form_invite_keys != false}
<ul>
{section name=i loop=$form_invite_keys}
<li><a {if $form_invite_keys[i].valid != true} title="Dieser Schlüssel wurde bereits verwendet" class="obsolete"{else} href="{$root}register/{$form_invite_keys[i].key}/" title="{$form_invite_keys[i].group_str}-Schlüssel"{/if} style="font-family: fixed, monospace;{if $form_invite_keys[i].valid != true} text-decoration: line-through;{/if}">{$form_invite_keys[i].key}</a></li>
{/section}
</ul>
{/if}
{if $form_invite_invites_left > 0 && !$form_invite_open_registration}
<p>Schlüssel können auf der <a href="{$root}register/">Registrierungsseite</a> eingelöst werden.</p>
{elseif $form_invite_open_registration}
<p>Schlüssel werden zur Registrierung nicht mehr benötigt.</p>
{elseif $form_invite_keys != false && $form_invite_admin != true}
<p>Alle deine Schlüssel wurden eingelöst.</p>
{/if}
{/if}
{if $form_invite_open_registration}
<form action="." method="post">
<table>
<tr><td>E-Mail-Adresse:</td><td><input type="email"></td></tr>
<tr><td></td><td><input type="submit" value="Einladen"></td></tr>
</table>
</form>
{/if}