<?php
if($user->can('admin_view')) {
    if($user->can('user_view')) {
        //@todo Seitenlimit        
        $content .= '<h2>Benutzer durchsuchen</h2>';
        $content .= '<form method="post" action="'.$context.'">';
        $usersearch = isset($_POST['username_search']) && strlen($_POST['username_search']) >= 1 ? cbg_output::cleanOutput($_POST['username_search']) : '';
        $content .= '<input type="search" results="0" placeholder="Benutzername" id="username_search" name="username_search" value="'.$usersearch.'">&nbsp;';
        $content .= '<input type="submit" value="Suchen">';
        $content .= '</form>';
        $results = 0;
        foreach($this->cbg->getUsers($usersearch) as $cur_user) {
            /* @var $cur_user cbg_user */
            $email = $user->can('user_mail_access') && $cur_user->getEmail() ? ' ('.$cur_user->getEmail().')' : '';
            $content .= cbg_output::createBlock(cbg_output::redirect_url($context, -1, 'show/'.$cur_user->getId().'/'), $this->getUserDetailed($cur_user, true).$email, $cur_user->getGroup()->getName());
            $results++;
        }
        if(!$usersearch || !$results) {
            if(!$results)
                $content .= '<p class="disabled">Keine passenden Benutzer gefunden.</p>';
        }
    }
    else {
        $this->displayError('403');
        return true;
    }
} else {
    $content .= 'To be implemented';
}
?>