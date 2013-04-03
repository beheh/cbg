<?php
$content .= '<h2>Berechtigungen testen</h2>';
/* switch($link) {
  case 'set':
  $group = isset($path[4]) ? $path[4] : '';
  if(isset($_POST['group']) && is_numeric($_POST['group']) && $_POST['group'] > 0 || $group && is_numeric($group) && $group > 0) {
  if(isset($_POST['group'])) {
  $group = $_POST['group'];
  }
  $this->cbg->setSessionData('user_group_temp', $group);
  header('Location: '.ROOT.'game/');
  return true;
  } else {
  $content .= '<h2>Berechtigungen testen</h2>';
  if($user->groupOverrideActive()) {
  $content .= '<form action="'.$context.'reset/" method="post">';
  $content .= '<table>';
  $content .= '<tr>';
  $content .= '<td>Berechtigungen:</td>';
  $content .= '<td>'.$user->getGroup(false, true)->getName().'-Rechte</td>';
  $content .= '</tr>';
  $content .= '<tr>';
  $content .= '<td>Rechte testen:</td>';
  $content .= '<td>'.$user->getGroup()->getName().'-Rechte</td>';
  $content .= '</tr>';
  $content .= '<tr>';
  $content .= '<td>&nbsp;</td>';
  $content .= '<td><input type="submit" value="Zurücksetzen"></td>';
  $content .= '</tr>';
  $content .= '</table>';
  $content .= '</form>';
  } else {
  $content .= '<form action="'.$context.'" method="post">';
  $content .= '<table>';
  $content .= '<tr>';
  $content .= '<td>Berechtigungen:</td>';
  $content .= '<td>'.$user->getGroup(false, true)->getName().'-Rechte</td>';
  $content .= '</tr>';
  $content .= '<tr>';
  $content .= '<td>Rechte testen:</td>';
  $content .= '<td><select name="group">';
  foreach($this->cbg->getGroups() as $group) {
  if($group->getId() == $user->getGroup(false, true)->getId())
  continue;
  $content .= '<option value="'.$group->getId().'">'.$group->getName().'-Rechte</option>';
  }
  $content .= '</select></td>';
  $content .= '</tr>';
  $content .= '<tr>';
  $content .= '<td>&nbsp;</td>';
  $content .= '<td><input type="submit" value="Rechte testen" style="padding: 0px 10px;"></td>';
  $content .= '</tr>';
  $content .= '</table>';
  $content .= '</form>';
  }
  }
  break;
  case 'reset':
  $this->cbg->setSessionData('user_group_temp', 0);
  header('Location: '.$contextupper);
  break;
  } */
?>
