<!DOCTYPE html> 
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link type="text/css" href="{$root}css/stylesheet.css" rel="stylesheet">
		<link rel="shortcut icon" href="{$root}css/favicon.ico" type="image/x-icon">
		<link rel="icon" href="{$root}css/favicon.ico" type="image/x-icon">
        <title>{$site_title}</title>
		<script type="text/javascript" src="{$root}js/jquery-1.4.2.min.js"></script>
		<script type="text/javascript">
			{literal}window.onload = function() {
				document.getElementById('email').focus();
			}{/literal} 
		</script>
    </head>
    <body>
        <div class="spacer">&nbsp;</div>
        <div class="emptybox">&nbsp;</div>
        <form method="post" action="{$root}forgot/{$username}/">
            <table id="content_login">
                <tr><td>&nbsp;</td></tr>
                <tr><td><img src="{$root}css/logo.png" alt="{$project_name}" height="80"></td></tr>
                <tr><td>&nbsp;</td></tr>
                <tr><td><label for="username">Benutzername<span class="highlight">*</span></label></th></tr>
                <tr><td><input type="text" name="username" id="username" style="width: 145px;" value="{$username}" disabled="disabled"></td></tr>
				        <tr><td><label for="email">E-Mail-Adresse<span class="highlight">*</span></label></th></tr>
                <tr><td><input type="email" name="email" id="email" style="width: 145px;"></td></tr>
                <tr><td>&nbsp;</td></tr>
                <tr><td><input type="submit" value="Daten prüfen" style="width: 145px;"></td></tr>
				        <tr><td>&nbsp;</td></tr>
                <tr><td><a href="{$root}">&laquo; Zur Startseite</a></td></tr>
				<tr><td>&nbsp;</td></tr>
				<tr><td class="copyright">{$copyright}</td></tr>
             </table>
        </form>
    </body>
</html>
