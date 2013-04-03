<?php
if($this->cbg->authSession()) {
    $user = $this->cbg->getCurrentUser();
    $settlements = array();
    if(isset($_POST['game_settlement_select'])) {
        $this->cbg->setCurrentSettlement($_POST['game_settlement_select']);
    }
    /*foreach($user->getSettlements() as $settlement) {
        $array = array();
        $array['id'] = $settlement->getId();
        $array['name'] = $settlement->getName();
        if($this->cbg->getCurrentSettlement())
            $array['current'] = $this->cbg->getCurrentSettlement()->getId() == $settlement->getId() ? true : false;
        $settlements[] = $array;
    }*/
    $this->smarty->assign('settlements', $settlements);
    $user_settlement = $this->cbg->getCurrentSettlement();
    $this->smarty->assign('user', array('name' => $this->getUserDetailed($user), 'admin' => $user->can('admin_view'), 'profile' => ROOT.'game/users/show/'.$user->getId().'/', 'settlements' => array(array('name' => 'Wipfhausen', 'id' => 1))));
    $content = '';
    $nocontent = false;
    if($user->can('admin_view')) {
        $linkpath[] = array('title' => 'Administration', 'link' => $context);
    } else {
        $linkpath[] = array('title' => 'Spiel', 'link' => $context);
    }
} else {
    $this->cbg->endSession();
    header('Location: ' . ROOT . 'login/?session');
    return false;
}
?>