<?php
require('cbg_output_form.class.php');

class cbg_output {

    /**
     * Stores cbg.
     *
     * @var cbg;
     */
    private $cbg;
    const seperator = '&raquo;';
    const controllers = 'include/controllers/';
    /**
     * Stores all debug messages with timestamp and variables.
     * @var $debug_messages
     */
    private $debug_messages = array();

    /**
     * Stores variables available to the controllers.
     * @var $vars
     */
    private $vars = array();
    private $has_error = false;

    public function __construct($cbg) {
        $this->cbg = $cbg;

        require('./include/libs/smarty/Smarty.class.php');
        //require($this->cbg->getRoot().'libs/feedwriter/FeedWriter.php');
        //Template
        $this->smarty = new Smarty();
        $this->smarty->template_dir = 'include/smarty/templates';
        $this->smarty->compile_dir = 'include/smarty/compiled';
        $this->smarty->cache_dir = 'include/smarty/cache';
        $this->smarty->plugins_dir = 'include/smarty/plugins';
        $this->smarty->config_dir = 'include/smarty/configs';
        $this->smarty->caching = Smarty::CACHING_OFF;

        //Fixed/standard content
        $this->smarty->assign('root', ROOT);
        $this->smarty->assign('site_title', $this->cbg->getProjectName());
        $this->smarty->assign('seperator', self::seperator);
        $this->smarty->assign('servertime', date(cbg_output::getFormat('fulltime'), $this->cbg->getServertime()));
        $milli = $this->cbg->getServertime(4);
        $this->smarty->assign('servermilli', ($milli - floor($milli)) * 1000);
        $this->smarty->assign('serverdate', date(cbg_output::getFormat('date'), $this->cbg->getServertime()));
        $this->smarty->assign('project_name', $this->cbg->getProjectName());
        $this->smarty->assign('project_version', $this->cbg->getProjectVersion());
        $this->smarty->assign('copyright', '&copy; '.date('Y').' <a href="'.ROOT.'">'.$this->cbg->getCopyright().'</a>');
    }

    static function createBlock($url = '#', $heading='Heading', $description='Description.', $disabled=false) {
        $class = 'block';
        if($disabled) {
            $url = '#';
            $class = 'block_disabled';
        }
        $heading = $heading;
        $description = $description;
        $ret = '<p>';
        if(!$disabled) {
            $ret .= '<a href="'.$url.'" class="'.$class.'"><b>'.$heading.'</b><br>'.$description.'</a>';
        } else {
            $ret .= '<span class="'.$class.'"><b>'.$heading.'</b><br>'.$description.'</span>';
        }
        $ret .= '</p>';
        return $ret;
    }

    static function createIconLink($icon, $link, $title, $disabled=false) {
        if($disabled) {
            return cbg_output::getIcon($icon, $title, $disabled);
        } else {
            return '<a href="'.$link.'" title="'.$title.'">'.cbg_output::getIcon($icon, $title, $disabled).'</a>';
        }
    }

    static function getIcon($icon, $title = false, $disabled = false) {
        if($disabled) {
            $icon .= '_disabled';
            $title .= ' (nicht möglich)';
        }
        $alt = $title;
        if(!$title)
            $alt = 'Icon';
        $title = $title ? ' title="'.$title.'"' : '';
        return '<img src="'.ROOT.'css/img/icons/'.$icon.'.png" class="icon" alt="'.$alt.'"'.$title.' width="16" height="16">';
    }

    public function getCopyright() {
        return '&copy; '.date('Y', $this->cbg->getServertime()).' '.self::link(ROOT, $this->cbg->getCopyright());
    }

    public function getFormat($format) {
        switch($format) {
            case 'time':
                return 'H:i';
                break;
            case 'fulltime':
                return 'H:i:s';
                break;
            case 'date':
                return 'd.m.Y';
                break;
            case 'fulltimedate':
                return cbg_output::getFormat('fulltime').' '.cbg_output::getFormat('date');
                break;
            case 'timedate':
                return cbg_output::getFormat('time').' '.cbg_output::getFormat('date');
                break;
            case 'datetime':
                return cbg_output::getFormat('date').' '.cbg_output::getFormat('time');
                break;
            default:
                throw new UnexpectedValueException('Unknown format ('.$format.').');
                break;
        }
    }

    protected function getRemainingTime($seconds) {
        $time = '';
        $hours = 0;
        while($seconds >= 60 * 60) {
            $hours++;
            $seconds -= 60 * 60;
        }
        $minutes = 0;
        while($seconds >= 60) {
            $minutes++;
            $seconds -= 60;
        }
        return '<span>'.sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds).'</span>';
    }

    /*protected function makeRomanNumeral($number) {
        $str = '';
        $numbers = array('5' => 'V', '1' => 'I');
        while($number > 1000) {
          $number -= 1000;
          $str .= 'M';
          }
          if($number > 900) {
          $number -= 900;
          $str = substr($str, 0, strlen($str)-1).'CM';
          }
          while($number > 100) {
          $number -= 100;
          $str .= 'C';
          }
        foreach($numbers as $i => $s) {
            while($number >= $i) {
                $number -= $i;
                $str .= $s;
            }
        }
        return $str;
    }*/

    protected function parseOutput($output, $clean = false) {
        if($clean) {
            $output = htmlspecialchars($output);
        } else {
            $count = 0;
            $lines = explode('<br>', $output);
            $output = '';
            $pattern = array('/\[b\](.*?)\[\/b\]/i', '/\[i\](.*?)\[\/i\]/i', '/\[u\](.*?)\[\/u\]/i');
            $replace = array('<strong>$1</strong>', '<em>$1</em>', '<u>$1</u>');
            foreach($lines as $line) {
                $line = preg_replace($pattern, $replace, htmlspecialchars(trim($line)));
                if(substr($line, 0, 4) == '&gt;') {
                    $line = '<span class="quote">'.$line.'</span>';
                }
                if($count)
                    $output .= '<br>';
                $count++;
                $output .= $line;
            }
            $output = nl2br($output);
        }
        return $output;
    }

    static function cleanOutput($output, $length = 0) {
        $output = trim(strip_tags($output));
        if($length != 0)
            $output = substr($output, 0, $length);
        $output = htmlspecialchars($output);
        return $output;
    }

    public function parseHistory($history) {
        $time = '['.date($this->getFormat('time'), $history['time']).']';
        $title = 'Unbekannt';
        $url = '#';
        try {
            switch($history['type']) {
                case 'building':
                    $building = $this->cbg->getBuildingById($history['object']);
                    $title = $building->getName();
                    $url = 'game/settlements/';
                    if($history['details'] > 1) {
                        $title .= ' &raquo; Stufe '.$history['details'];
                    } else {
                        $title .= ' errichtet';
                    }
                    break;
                default:
                    break;
            }
        } catch(Exception $ex) {
            
        }
        $details = '<a href="'.ROOT.$url.'">'.$title.'</a>';
        return $time.' '.$details;
    }

    public function getFullHistory(cbg_user $user, $length = 0) {
        $user->update($this->cbg->getServertime());
        $content = '';
        $date = 0;
        $count = 0;
        foreach($user->getHistory($length) as $history) {
            $newdate = date($this->getFormat('date'), $history['time']);
            if($newdate != $date || !$count) {
                if($count) {
                    $content .= '</ol>';
                }
                $date = $newdate;
                $content .= '<ol>';
                if($date == date($this->getFormat('date'), $this->cbg->getServertime())) {
                    $title = 'Heute';
                } else if($date == date($this->getFormat('date'), $this->cbg->getServertime() - 24 * 60 * 60)) {
                    $title = 'Gestern';
                } else {
                    $title = $newdate;
                }
                $content .= '<li><strong>'.$title.':</strong></li>';
            }
            $content .= '<li>'.$this->parseHistory($history).'</li>';
            $count++;
        }
        if(!$count) {
            $content .= '<ol>';
            $content .= '<li><span class="unimportant">Keine Ereignisse vorhanden.</span></li>';
        }
        $content .= '</ol>';
        return $content;
    }

    public function displayError($error, $nolink = false) {
        $error_string = '';
        try {
            switch($error) {
                case '403':
                    header('HTTP/1.1 403 Forbidden');
                    $error_string = 'Du hast keine Berechtigungen, um auf die angeforderte Seite zuzugreifen.';
                    break;
                case '404':
                    header('HTTP/1.1 404 Not Found');
                    $error_string = 'Die angeforderte Seite konnte nicht gefunden werden.';
                    break;
                case '500':
                case 'internal':
                    $error_string = 'Ein interner Serverfehler ist aufgetreten. Bitte versuche es später erneut.';
                    break;
                case 'referer':
                    $error_string = 'Dieser Aufruf wurde aus Sicherheitsgründen blockiert, da er von einer anderen Seite stammt.';
                    break;
                case 'malicious':
                    $error_string = 'Dieser Aufruf wurde aus Sicherheitsgründen blockiert und erfasst.';
                    break;
                case 'data':
                    $error_string = 'Dieser Aufruf ist nicht mehr gültig, da er veraltete Daten enthält.';
                    break;
                case 'database':
                    $error_string = 'Es konnte keine Verbindung zur Datenbank hergestellt werden. Bitte versuche es später erneut.';
                    break;
                default:
                    throw new UnexpectedValueException('unknown error ('.$error.').');
                    break;
            }
        } catch(UnexpectedValueException $ex) {
            $error_string = 'Ein unbekannter Fehler ist aufgetreten ('.$error.').';
        }
        $error_full = '<p><span class="error">'.$error_string.'</span></p>';
        if(!$nolink) {
            if($this->cbg->getCurrentUser() == false) {
                $error_full .= '<p><a href="'.ROOT.'" class="important">Zurück zur Startseite</a></p>';
            } else {
                $error_full .= '<p><a href="'.ROOT.'game/" class="important">Zurück zum Spiel</a></p>';
            }
        }
        echo $error_full;
        $this->displayDebugLog();
        $this->has_error = true;
    }

    protected function getCount($type, $seconds) {
        switch($type) {
            case 'second':
                return $seconds;
                break;
            case 'minute':
                return round($seconds / 60, 0);
                break;
            case 'hour':
                return round($seconds / 60 / 60, 0);
                break;
            case 'day':
                return round($seconds / 60 / 60 / 24, 0);
                break;
            default:
                throw new UnexpectedValueException('unknown count ('.$type.').');
                break;
        }
    }

    protected function getUserDetailed(cbg_user $user, $nolink = false) {
        $group = $user->getGroup(false, false);
        $string = '<img src="'.ROOT.'css/img/ranks/'.$group->getImage().'" alt="'.$group->getName().'" title="'.$group->getName().'" class="rank" height="16" width="16">';
        if($nolink) {
            $string .= $user->getUsername();
        } else {
            $string .= '<a href="'.ROOT.'game/users/show/'.$user->getId().'/" title="'.$group->getName().' '.$user->getUsername().'" class="profile">'.$user->getUsername().'</a>';
        }
        return $string;
    }

    protected function objectBox(cbg_object $object, $count = 0, $link='#', $silhouette=false) {
        $title = $object->getName();
        $image = $object->getImage();
        $description = $object->getDescription();
        $count .= 'x';
        if($silhouette) {
            $image .= '_silhouette';
            $title = '???';
            $count = '???';
        }
        if(!$description)
            $description = $title;
        $box = '<a href="'.$link.'" class="object" title="'.$description.'">';
        $box .= $title.'<br>';
        $box .= '<img src="'.ROOT.'css/img/objects/'.$image.'.png" width="32" height=32><br>';
        $box .= '<span style="font-size: 0.9em">'.$count.'</span>';
        $box .= '</a>';
        return $box;
    }

    protected function getPath() {
        if(isset($_GET['path']) && !empty($_GET['path'])) {
            $path_plain = htmlspecialchars($_GET['path']);
            $path_plain = rtrim($path_plain, '/');
            $path_raw = explode('/', $path_plain);
            $path = array();
            foreach($path_raw as $key => $value) {
                if(empty($value) && $key == sizeof($path_raw) - 1)
                    continue;
                $path[] = $value;
            }
        } else {
            $path = array();
        }
        return $path;
    }

    public function display() {
        $path = $this->getPath();
        $context = ROOT;

        header('X-UA-Compatible: chrome=1;'); //Google Chrome Frame
        //ob_start("ob_gzhandler"); //gzip
        //Caching
        /* if($this->cbg->config['runtime']['cache']) {
          //session_cache_limiter('private');
          } */

        $malicious = false;
        $badword = array('delete from', 'select *', 'drop table', 'drop database');
        foreach($_REQUEST as $req) {
            foreach($badword as $word) {
                if(stripos($req, $word) !== false) {
                    $malicious = true;
                    break;
                }
            }
        }

        if(MAINTENANCE != '' && !$this->cbg->isSuperSession()) {
            $this->smarty->assign('site_title', $this->cbg->getProjectName().' '.self::seperator.' Wartung');
            $message = (MAINTENANCE !== true) ? '<p>'.MAINTENANCE.'</p>' : '';
            $this->smarty->assign('fatal_error', '<h3>Temporäre Wartung</h3>'.$message.'<p>Bitte versuche es später erneut.</p>');
            $output = $this->smarty->fetch('cbg_login.tpl');
        } else if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], ROOT) !== 0 && count($path) > 1) {
            $this->displayError('referer');
            return true;
        } else if($malicious) {
            $this->cbg->logAttack();
            $this->displayError('malicious');
            return true;
        } else {
            $linkpath = array();
            $output = '';

            $folder = self::controllers;
            $context = ROOT;
            $params = array();

            $this->include_file($folder.'_init.php', compact('context'));

            if(count($path) == 0) {
                if(file_exists($folder.'_index.php')) {
                    $this->include_file($folder.'_index.php', compact('context', 'params'));
                }
            }
            $length = count($path);
            for($i = 0; $i < $length; ++$i) {
                $element = $path[$i];
                $context .= $element.'/';
                if(strpos($element, '_') === 0) {
                    $this->debug('not allowed', compact('element'));
                    $this->displayError('403');
                    return true;
                } elseif(is_dir($folder.$element)) {
                    $folder .= $element.'/';
                    if(!$this->include_file($folder.'_init.php', compact('context', 'params'))) {
                        $this->debug('File above aborted');
                        return;
                    }
                    if($i == $length - 1) {
                        if(is_file($folder.'_index.php')) {
                            $this->include_file($folder.'_index.php', compact('context', 'params'));
                        } elseif(is_file($folder.'_default.php')) {
                            $this->include_file($folder.'_default.php', compact('context', 'params'));
                        }
                    }
                } elseif(is_file($folder.$element.'.php') && $i == $length - 1) {
                    $this->debug('DEPRECATED: Direct file access without folder');
                    $this->include_file($folder.$element.'.php', compact('context', 'params'));
                } elseif(is_dir($folder.'_default')) {
                    $params[] = $element;
                    $folder .= '_default/';
                    if(!$this->include_file($folder.'_init.php', compact('context', 'params'))) {
                        $this->debug('File above aborted');
                        return;
                    }
                    if($i == $length - 1) {
                        $this->include_file($folder.'_index.php', compact('context', 'params'));
                    }
                } elseif(is_file($folder.'_default.php') && $i == $length - 1) {
                    $params[] = $element;
                    $this->include_file($folder.'_default.php', compact('context', 'params'));
                } else {
                    $this->debug('not found', compact('element', 'context', 'path'));
                    $this->displayError('404');
                    return;
                }
            }
            // Wenn kein Fehler aufgetreten
            if(!$this->has_error) {
                // _finalize-Dateien ausführen
                $count = substr_count($folder, '/');
                $end = substr_count(self::controllers, '/');
                for($i = $count; $i >= $end; $i--) {
                    $this->include_file($folder.'_finalize.php');
                    $folder = self::redirect_url($folder, -1);
                }
            }
        }
        if(!empty($path_plain) && substr($path_plain, -1, 1) != '/' && empty($_POST)) {
            header('HTTP/1.1 302 Moved Permanently');
            header('Location: '.ROOT.$path_plain.'/');
            return true;
        }
        // Wenn keine Fehler aufgetreten sind ausgeben.
        if(!$this->has_error) {
            print($this->vars['output']);
            $this->displayDebugLog();
        }
    }

    /**
     * Führt eine Datei aus. Dabei werden automatisch Variablen gesetzt und
     * alle bereits vorhandenen Variablen auf neue Werte gesetzt.
     */
    private function include_file($file, $temp_vars=array()) {
        if(is_file($file)) {
            // Variablen laden
            extract($this->vars);
            // Temporäre Variablen laden
            extract($temp_vars);
            // Bestimmte Variablen definieren
            $cbg = $this->cbg;
            $smarty = $this->smarty;
            // Nachricht absetzen
            $this->debug('Executing: '.$file, array_diff_key(get_defined_vars(), compact('file', 'temp_vars')));
            // Datei ausführen
            $ret_val = include($file);
            // Variablen updaten
            // keine lokalen Variablen dieser Funktion speichern und keine
            // temporären Variablen.
            $this->vars = array_diff_key(get_defined_vars(), compact('file', 'temp_vars', 'ret_val'), $temp_vars);
            // Rückgabewert
            return $ret_val;
        } else {
            $this->debug('Skipping: '.$file, array('Cause' => 'Doesn\'t exist'));
            return true;
        }
    }

    private function displayDebugLog() {
        // Wenn Debug aktiviert ist, dann das Debug-Log ausgeben.
        if($this->cbg->isDebug()) {
            echo '<table class="debug-log"><tr><th>Nr.</th><th>Zeit</th><th>Nachricht</th></tr>';
            $i = 1;
            foreach($this->debug_messages as $message) {
                echo '<tr>';
                echo '<td><pre>'.$i.'</pre></td>';
                echo '<td class="time"><pre>'.$message['time'].'</pre></td>';
                echo '<td class="content"><pre>';
                echo $message['content'];
                if(count($message['additional']) != 0) {
                    echo PHP_EOL;
                    echo '<b><a onclick="$(\'#vars'.$i.'\').toggle();">Variablen</a></b>'.PHP_EOL;
                    echo '<span id="vars'.$i.'">';
                    foreach($message['additional'] as $key => $value) {
                        if(gettype($value) == 'array') {
                            echo '$'.$key.' = '.htmlspecialchars(print_r($value, true)).PHP_EOL;
                        } /* else {
                          echo '$'.$key.' = '.htmlspecialchars((string) $value).PHP_EOL;
                          } */
                    }
                    echo '</span>';
                }
                echo '</pre></td></tr>';
                $i++;
            }
            echo '</table>';
            ?>
            <style type="text/css" rel="stylesheet">
                .debug-log {
                    float: left;
                    width: 100%;
                    padding: 0 1cm;
                    margin-top: 1cm;
                }
                .debug-log th {
                    font-weight: bold;
                }
                .debug-log td {
                    text-align: left;
                }
                .debug-log pre {
                    margin: 0;
                }
                .debug-log td.time {
                    width: 100px;
                }
                .debug-log tr:nth-child(odd) {
                    background: #aaa;
                }
                .debug-log tr:nth-child(even) {
                    background: #bbb;
                }
            </style>
            <script type="text/javascript" src="<?php echo ROOT; ?>js/jquery-1.7.1.min.js"></script>
            <script type="text/javascript">
                $(document).ready(function() {
                    $('.debug-log span').hide();
                });
            </script>
            <?php
        }
    }

    protected function debug($message, $additional = array()) {
        $this->debug_messages[] = array(
            'content' => $message,
            'time' => time(),
            'additional' => $additional,
        );
    }

    static function redirect($context, $move_up, $new_location = '') {
        header('Location: '.cbg_output::redirect_url($context, $move_up, $new_location));
    }

    static function redirect_url($context, $move_up, $new_location='') {
        $context = trim($context, '/');
        if($move_up < 0) {
            $context = explode('/', $context);
            $context = array_slice($context, 0, count($context) + $move_up);
            $context = implode('/', $context);
        }
        $path = $context.'/'.$new_location;
        return $path;
    }

    static function css_image($name, $alt = '', $width = null, $height = null, $attrs=array()) {
        if(!$alt)
            $alt = $name;
        if(!isset($attrs['width']) && $width !== null)
            $attrs['width'] = $width;
        if(!isset($attrs['height']) && $width !== null)
            $attrs['height'] = $height;
        return self::html_element('img', null, array_merge(array('src' => ROOT.'css/'.$name, 'alt' => $alt), $attrs));
    }

    static function link($target, $title, $attrs=array()) {
        return self::html_element('a', $title, array_merge(array('href' => $target), $attrs));
    }

    static function html_element($type, $content=null, $attrs=array()) {
        $output = '<'.$type;
        foreach($attrs as $attr => $value) {
            $output .= ' '.$attr.'="'.$value.'"';
        }
        $output .= '>';
        if($content !== null) {
            $output .= $content;
            $output .= '</'.$type.'>';
        }
        return $output;
    }

    static function url($target = '') {
        return ROOT.$target;
    }

}
?>
