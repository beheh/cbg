<!DOCTYPE html>
<html>
    <head>
        <title>{$site_title}</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link type="text/css" href="{$root}css/stylesheet.css" rel="stylesheet">
        <link type="text/css" href="{$root}css/tipsy.css" rel="stylesheet">
        <link rel="shortcut icon" href="{$root}css/favicon.ico" type="image/x-icon">
        <link rel="icon" href="{$root}css/favicon.ico" type="image/x-icon">
        <script src="{$root}js/jquery-1.4.4.js"></script>
        <script src="{$root}js/jquery.tipsy.js"></script>
        <script type="text/javascript">
                {literal}$(document).ready(function() {
                        $('#username').tipsy({gravity:'w', trigger:'focus'});
                        $('#email').tipsy({gravity:'w', trigger:'focus'});
                        $('#key').tipsy({gravity:'w', trigger:'focus'});
                        $('#password').tipsy({gravity:'w', trigger:'focus'});
                        $('#password2').tipsy({gravity:'w', trigger:'focus'});
                        if($('#username').hasClass('error') || $('#username').val() == '') {
                                $('#username').focus();
                        }
                });{/literal}
        </script>
    </head>
    <body>
        <div class="spacer">&nbsp;</div>
        {if $register_failure != ""}
        <div class="infobox"><p class="error">{$register_failure}</p></div>
        {elseif $register_info != ""}
        <div class="infobox"><p class="success">{$register_info}</p></div>
        {else}
        <div class="emptybox">&nbsp;</div>
        {/if}
		{if $register_text != ""}
		<table id="content_login">
			<tr><td>&nbsp;</td></tr>
			<tr><td><img src="{$root}css/logo.png" alt="{$project_name}"></td></tr>
			<tr><td>&nbsp;</td></tr>
			<tr><td>{$register_text}</td></tr>
			<tr><td>&nbsp;</td></tr>
			<tr><td><a href="{$root}">&laquo; Zur Startseite</a></td></tr>
			<tr><td>&nbsp;</td></tr>
			<tr><td class="copyright">{$copyright}</td></tr>
		</table>
		{else}
        <form method="post" action=".">
            <table id="content_login">
                <tr><td>&nbsp;</td></tr>
                <tr><td><img src="{$root}css/logo.png" alt="{$project_name}" height="80"></td></tr>
                <tr><td>&nbsp;</td></tr>
                <tr><td><label for="username">Benutzername<span class="highlight">*</span></label></td></tr>
                <tr><td><input type="text" name="username" id="username" original-title="Der Benutzername muss zwischen 3 und 12 Zeichen lang sein und darf Buchstaben, Zahlen und einige Sonderzeichen enthalten." style="width: 145px;"{if $register_value_user != ""} value="{$register_value_user}"{/if}{if $register_user_error} class="error" {/if}></td></tr>
				<tr><td><label for="email">E-Mail-Adresse<span class="highlight">*</span></label></td></tr>
                <tr><td><input type="email" name="email" id="email" original-title="Es muss eine gültige E-Mail-Adresse zur Aktivierung des Kontos angegeben werden." style="width: 145px;"{if $register_value_email != ""} value="{$register_value_email}"{/if}{if $register_email_error} class="error" {/if}></td></tr>
				{if !$open_registration || $register_value_key != "" || $register_key_show}<tr><td><label for="key">{if !$open_registration}Betaschlüssel<span class="highlight">*</span>{else}Referenzschlüssel{/if}</label></td></tr>
                <tr><td><input type="text" name="key" id="key" maxlength="19" original-title="{if !$open_registration}Ein Betaschlüssel ist von Administratoren und einigen Spielern erhältlich und zur Registrierung erforderlich.{else}Ein Referenzschlüssel kann bei der Registrierung erweiterte Rechte und Funktionen freischalten.{/if}" style="width: 145px;"{if $register_value_key != ""} value="{$register_value_key}" {/if}{if $register_key_error} class="error"{/if}{if $register_key_disabled == true} disabled{/if}></td></tr>{/if}
                <tr><td><label for="password">Passwort<span class="highlight">*</span></label></td></tr>
                <tr><td><input type="password" name="password" id="password" original-title="Das Passwort kann nach der Anmeldung geändert werden. Groß-/Kleinschreibung wird beachtet." value="" style="width: 145px;"{if $register_password_error} class="error" {/if}></td></tr>
				<tr><td><label for="password2">Passwort wiederholen<span class="highlight">*</span></label></td></tr>
                <tr><td><input type="password" name="password2" id="password2" original-title="Eine erneute Eingabe des Passworts wird benötigt, um Tippfehler zu vermeiden." value="" style="width: 145px;"{if $register_password_error} class="error" {/if}></td></tr>
                <tr><td>&nbsp;</td></tr>
                <tr><td><input type="submit" value="Registrieren" style="width: 145px;"></td></tr>
				<tr><td>&nbsp;</td></tr>
				{if $open_registration && $register_value_key == "" && !$register_key_show}<tr><td><a href="{$context}showkey/" class="unimportant">Schlüssel erhalten?</a></td></tr>{/if}
                <tr><td>&laquo; <a href="{$root}">Zur Startseite</a></td></tr>
				<tr><td>&nbsp;</td></tr>
                <tr><td class="copyright">{$copyright}</td></tr>
             </table>
        </form>
		{/if}
    </body>
</html>
