<?php

/**
 * Formularklasse um Formulare darzustellen.
 */
class cbg_output_form {
    const DISPLAY_NORMAL = 1;
    const DISPLAY_TABULAR = 2;

    private $content;
    private $title;
    private $context;
    private $type;
    private $extra;
    private $submit = 0;
    private $form_id;
    private $options = array();

    public function __construct($id, $context, $title, $type=self::DISPLAY_NORMAL, $extra=array()) {
        $this->content = array();
        $this->title = $title;
        $this->context = $context;
        $this->type = $type;
        $this->options = $extra;
        $this->form_id = $id;
    }

    private function get($name, $default=null) {
        if(isset($this->options[$name])) {
            return $this->options[$name];
        } else {
            return $default;
        }
    }

    public function set_option($name, $value) {
        $this->options[$name] = $value;
    }

    protected function add($type, $name, $attrs=array(), $extra=array()) {
        $this->content[] = compact('type', 'name', 'attrs', 'extra');
    }

    public function button($name, $title, $target, $attrs=array()) {
        $this->add('button', $name, array_merge($attrs, array('href' => $target)));
    }

    public function text($name, $default='', $label=null, $attrs=array()) {
        $this->add('text', $name, array_merge($attrs, array('value' => $default)), array('label' => $label));
    }

    public function password($name, $label=null, $attrs=array()) {
        $this->add('password', $name, $attrs, array('label' => $label));
    }

    public function date($name, $default, $label=null, $attrs=array()) {
        $this->add('date', $name, array_merge($attrs, array('value' => $default)), array('label' => $label));
    }

    public function datetime($name, $default, $label=null, $attrs=array()) {
        $this->add('datetime', $name, array_merge($attrs, array('value' => $default)), array('label' => $label));
    }

    public function year($name, $default, $label=null, $attrs=array()) {
        $this->add('year', $name, array_merge($attrs, array('value' => $default)), array('label' => $label));
    }

    public function month($name, $default, $label=null, $attrs=array()) {
        $this->add('month', $name, array_merge($attrs, array('value' => $default)), array('label' => $label));
    }

    public function day($name, $default, $label=null, $attrs=array()) {
        $this->add('day', $name, array_merge($attrs, array('value' => $default)), array('label' => $label));
    }

    public function color($name, $default, $label=null, $attrs=array()) {
        $this->add('color', $name, array_merge($attrs, array('value' => $default)), array('label' => $label));
    }

    public function email($name, $default, $label=null, $attrs=array()) {
        $this->add('email', $name, array_merge($attrs, array('value' => $default)), array('label' => $label));
    }

    public function number($name, $default, $label=null, $attrs=array()) {
        $this->add('number', $name, array_merge($attrs, array('value' => $default)), array('label' => $label));
    }

    public function search($name, $default, $label=null, $attrs=array()) {
        $this->add('search', $name, array_merge($attrs, array('value' => $default)), array('label' => $label));
    }

    public function range($name, $default, $label=null, $attrs=array()) {
        $this->add('range', $name, array_merge($attrs, array('value' => $default)), array('label' => $label));
    }

    public function checkbox($name, $default, $label=null, $attrs=array()) {
        $this->add('checkbox', $name, array_merge($attrs, array('value' => $default)), array('label' => $label));
    }

    public function select($name, $default, $label=null, $attrs=array()) {
        $this->add('select', $name, array_merge($attrs, array('value' => $default)), array('label' => $label));
    }

    public function radio($name, $default, $label=null, $attrs=array()) {
        $this->add('radio', $name, array_merge($attrs, array('value' => $default)), array('label' => $label));
    }

    public function text_area($name, $default, $label=null, $attrs=array()) {
        $this->add('textarea', $name, $attrs, array('content' => $default,
            'label' => $label));
    }

    public function submit($title='', $attrs=array()) {
        if(!$title) $title = $this->title;
        $name = 'submit'.$this->submit;
        $this->submit++;
        $this->add('submit', $name, array_merge($attrs, array('value' => $title)));
    }

    public function new_line() {
        $this->add('newline', null);
    }

    public function raw_html($html) {
        $this->add('html', $html);
    }

    public function display($return=true) {
        $output = '<form method="post" action="'.$this->context.'" id="'.$this->form_id.'">'.PHP_EOL;
        if($this->type == self::DISPLAY_TABULAR && !$this->get('tabular_body_only', false)) {
            $class = $this->get('table-class', false);
            if ($class)
                $class = ' '.$class;
            $output .= '<table class="form-table'.$class.'">'.PHP_EOL;
        }
        foreach($this->content as $name => $element) {
            $label = $this->label($element);
            switch($this->type) {
                case self::DISPLAY_NORMAL:
                    $output .= $label.$this->element($element).PHP_EOL;
                    break;
                case self::DISPLAY_TABULAR:
                    $output .= '<tr>';
                    if($label) {
                        $output .= '<td>'.$label;
                        if($this->get('tabular_two_cell', false)) {
                            $output .= '</td><td>';
                        } else if($this->get('tabular_two_row', false)) {
                            $output .= '</td></tr>'.PHP_EOL.'<tr><td>';
                        }
                    } else {
                        $output .= '<td>';
                    }
                    $output .= $this->element($element).'</td></tr>'.PHP_EOL;
                    break;
                default:
                    break;
            }
        }
        if($this->type == self::DISPLAY_TABULAR && !$this->get('tabular_body_only', false)) {
            $output .= '</table>'.PHP_EOL;
        }
        $output .= '</form>';
        if($return) {
            return $output;
        } else {
            echo $output;
        }
    }

    private function element($element) {
        $type = $element['type'];
        $name = $element['name'];
        switch($type) {
            case 'html':
                return $name;
                break;
            case 'newline':
                return '&nbsp;';
                break;
            case 'select':
                $output = '<select id="'.$this->get_id($name).'" name="'.$name.'"';
                foreach($element['attrs'] as $attr => $value) {
                    if($attr != 'name' && $attr != 'id')
                        $output .= ' '.$attr.'="'.$value.'"';
                }
                $output .= '>';
                foreach($element['items'] as $name => $title) {
                    $output .= '<option value="'.$name.'">'.$title.'</option>';
                }
                $output .= '</select>';
                return $output;
            case 'textarea':
                $output = '<textarea id="'.$this->get_id($name).'" name="'.$name.'"';
                foreach($element['attrs'] as $attr => $value) {
                    if($attr != 'name' && $attr != 'id')
                        $output .= ' '.$attr.'="'.$value.'"';
                }
                $output .= '>';
                return $output;
            default:
                $output = '<input id="'.$this->get_id($name).'" type="'.$type.'" name="'.$name.'"';
                foreach($element['attrs'] as $attr => $value) {
                    if($attr != 'name' && $attr != 'type' && $attr != 'id')
                        $output .= ' '.$attr.'="'.$value.'"';
                }
                $output .= '>';
                return $output;
                break;
        }
    }

    private function get_id($name) {
        return 'form_'.$this->form_id.'_'.$name;
    }

    private function label($element) {
        if(!isset($element['extra']['label']))
            return '';
        switch($element['type']) {
            case 'newline':
                return '';
            case 'html':
                return '';
            default:
                $output = '<label for="'.$this->get_id($element['name']).'">';
                $output .= $element['extra']['label'];
                if($this->get('highlight_required', false) && $element['attrs']['required']) $output .= '<span class="highlight">*</span>';
                $output .= '</label>';
                return $output;
        }
    }

}

?>