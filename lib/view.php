<?php

class View {

    const EXT = '.ctp';

    private $viewVars = array();
    private $prev = '';

    public $route;

    private $headScripts;
    private $controller;

    private $form = false;

    public function __construct($route, $controller) {
        $this->route = $route;
        $this->controller = $controller;
    }

    public function __call($name, $args) {
        if (method_exists($this, $name)) {
            parent::__call($name, $args);
        } else {
            return '`'.$name.'` not found.';
        }
    }

    public function render($view, $layout) {
        $content = $this->fetch($view);
        if ($layout) {
            $this->set('__content', $content);
            echo $this->fetch($layout);
        } else {
            echo $content;
        }
    }

    public function partial($view, $data=array()) {
        $html = '';
        $path = APP::$ROOT . "/app/views/{$this->route->controller}/{$view}" . self::EXT;
        if (file_exists($path)) {
            $prev = ob_get_contents();
            ob_clean();

            if (! empty($data)) {
                foreach ($data as $name=>$value) {
                    $$name = $value;
                }
            }

            include $path;
            $html = ob_get_contents();
            ob_clean();

            echo $prev;
        }

        return $html;
    }

    public function fetch($view) {
        $view .= self::EXT;
        $html = '';
        $base = APP::$ROOT . "/app/views/";
        
        if (file_exists($base.$view)) {
            $this->prev = ob_get_contents();
            ob_clean();
            foreach($this->viewVars as $name=>$value) {
                $$name = $value;
            }
            include $base.$view;
            $html = ob_get_contents();
            ob_clean();
        } else {
            $html = "View `{$view}` not found.";
        }

        return $html;
    }

    public function element($view, $data=array()) {
        if (! empty($data)) {
            foreach ($data as $k=>$v)
                $this->set($k, $v);
        }
        $element = $this->fetch("elements/{$view}");
        return $this->prev.$element;
    }

    public function set($name, $value) {
        $this->viewVars[$name] = $value;
    }

    public function favicon() {
        return '<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">';
    }

    public function css($name, $media='all', $attrs=array()) {
        $path = $this->parsePath($name, "/css/{$name}.css");
        return '<link rel="stylesheet" type="text/css" href="'.$path.'" media="'.$media.'"'.$this->parseAttrs($attrs).'>';
    }

    public function img($name, $alt="", $attrs=array()) {
        $path = $this->parsePath($name, "/img/$name");
        return '<img src="'.$path.'" alt="'.$alt.'"'.$this->parseAttrs($attrs).'>';
    }

    public function js($src, $attrs=array()) {
        $path = $this->parsePath($src, "/js/{$src}.js");
        return '<script type="text/javascript" src="'.$path.'"'.$this->parseAttrs($attrs).'></script>';
    }

    public function asset($type, $file, $attrs=array()) {
        $path = $this->parsePath($file, "/{$type}/assets/{$file}.{$type}");
        switch($type) {
            case 'js':
                return '<script type="text/javascript" src="'.$path.'"'.$this->parseAttrs($attrs).'></script>';
                break;

            case 'css':
                return '<link rel="stylesheet" type="text/css" href="'.$path.'"'.$this->parseAttrs($attrs).'>';
                break;
        }
    }

    public function link($html, $url=null, $attrs=array()) {
        if ($url === null) {
            $url = $html;
        }

        // confirm links
        if (isset($attrs['confirm'])) {
            $confirm = htmlentities($attrs['confirm']);
            $attrs['onclick'] = "return confirm('{$confirm}');";
            unset($attrs['confirm']);
        }

        // remote links
        if (isset($attrs['remote'])) {
            unset($attrs['remote']);
            
            $attrs['data-url'] = $url;
            $attrs['data-remote'] = 'true';
            if (isset($confirm)) {
                $attrs['data-confirm'] = $confirm;
                unset($attrs['onclick']);
            }

            if (isset($attrs['data'])) {
                foreach($attrs['data'] as $k=>$v) {
                    if (is_array($v) || is_object($v)) {
                        $v = htmlentities(json_encode($v));
                    }
                    $attrs["data-{$k}"] = $v;
                }
                unset($attrs['data']);
            }
            $url = 'javascript:void(0)';
        }
        return '<a href="'.$url.'"'.$this->parseAttrs($attrs).'>'.$html.'</a>';
    }

    public function head($type, $url=null) {

        // get
        if ($url === null) {
            $html = "";
            if (isset($this->head[$type])) {
                foreach ($this->head[$type] as $url) {
                    switch($type) {
                        case 'js':
                            $url = $this->parsePath($url, "/js/{$url}.js");
                            $html .= '<script type="text/javascript" src="'.$url.'"></script>'."\n";
                            break;

                        case 'css':
                            if (is_array($url)) {
                                $media = isset($url['media']) ? $url['media'] : 'all';
                                $url = $this->parsePath($url['href'], "/css/" . $url['href'] . ".css");
                            } else {
                                $url = $this->parsePath($url, "/css/{$url}.css");
                                $media = 'all';
                            }
                            $html .= '<link rel="stylesheet" type="text/css" href="'.$url.'" media="'.$media.'">';
                            break;

                        case 'meta':
                            if (isset($url['name'])) {
                                $name = $url['name'];
                                $content = $url['content'];
                            } else {
                                $name = $url[0];
                                $content = $url[1];
                            }
                            $html .= $this->meta($name, $content);
                            break;
                    }
                }
            }

            return $html;
        // set
        } else {

            if (! isset($this->head[$type])) {
                $this->head[$type] = array();
            }

            $this->head[$type][] = $url;
        }
    }

    public function meta($name, $content) {
        return '<meta name="'.$name.'" content="'.$content.'">';
    }

    public function form($name, $action, $options = array()) {
        $this->form = $name;

        $options['action'] = $action;

        if (! isset($options['method'])) {
            $options['method'] = 'post';
        }

        return '<form' . $this->parseInputOptions($options) . '>';
    }
    public function formEnd() {
        $this->form = false;
        return '</form>';
    }

    public function email($name, $options=array()) {
        $options['type'] = 'email';
        return $this->input($name, $options);
    }

    public function password($name, $options=array()) {
        $options['type'] = 'password';
        return $this->input($name, $options);
    }

    public function text($name, $options=array()) {
        return $this->input($name, $options);
    }

    public function input($name, $options=array()) {
        $options['name'] = $this->parseInputName($name);
        if (! isset($options['id'])) {
            $options['id']   = $this->parseInputId($options['name']);
        }

        $html = '';

        // type
        if (! isset($options['type'])) {
            $options['type'] = 'text';
        };

        // label
        if (isset($options['label'])) {
            if (! isset($options['label-options'])) {
                $options['label-options'] = array();
            }

            $html .= $this->label($options['name'], $options['label'], $options['label-options']);
        }
        unset($options['label']);
        unset($options['label-options']);

        // value
        if (! isset($options['value'])) {
            $options['value'] = '';
        }

        $options = $this->parseInputOptions($options);
        $html .= "<input{$options}>";

        return $html;
    }

    public function textarea($name, $options=array()) {
        $html = '';

        // name
        $options['name'] = $this->parseInputName($name);
        $options['id']   = $this->parseInputId($options['name']);

        // label
        if (isset($options['label'])) {
            $html .= $this->label($options['name'], $options['label']);
            unset($options['label']);
        }

        // value
        $value = $options['value'];
        unset($options['value']);

        $options = $this->parseInputOptions($options);
        $html .= "<textarea{$options}>{$value}</textarea>";

        return $html;
    }

    public function label($name, $text, $options=array()) {
        $options = array_merge($options, array(
            'for' => $this->parseInputId($name)
        ));
        return '<label'.$this->parseInputOptions($options).'>'.$text.'</label>';
    }

    public function hidden($name, $value, $options=array()) {
        $options['value'] = $value;
        $options['type']  = 'hidden';
        return $this->input($name, $options);
    }

    public function checkbox($name, $options=array()) {
        $options['value'] = 1;
        $options['type']  = 'checkbox';
        if ((int)@$options['checked']>0) {
            $options['checked'] = 'checked';
        } else {
            unset($options['checked']);
        }
        $options['type']  = 'checkbox';
        $options['label-options'] = array('class'=>'checkbox');
        
        return $this->hidden($name, 0, array('id'=>$this->parseInputId($name)."_hidden")).
               $this->input($name, $options);
    }

    public function select($name, $selOptions, $options=array()) {
        $name = $this->parseInputName($name);
        $id   = $this->parseInputId($name);

        $html = '';

        if (isset($options['label'])) {
            $html .= $this->label($name, $options['label']);
            unset($options['label']);
        }
        $options['name'] = $name;
        $options['id']   = $id;

        $html .= '<select'.$this->parseInputOptions($options).'>';

        if (isset($options['empty'])) {
            if($options['empty']) {
                $html .= '<option value="">'.$options['empty'].'</option>';
            }
        } else {
            $html .= '<option value="">---</option>';
        }

        array_walk($selOptions, function(&$a, $b, $value){
            $selected = '';
            if ($value == $b) {
                $selected = ' selected="selected"';
            }

            $a = '<option value="'.$b.'"'.$selected.'>'.$a.'</option>';
        }, @$options['value']);
        $html .= implode('', $selOptions) . '</select>';

        return $html;
    }

    public function submit($label, $options=array()) {
        $options = array_merge($options, array(
            'value' => $label,
            'type'=>'submit'
        ));
        $options = $this->parseInputOptions($options);
        return "<input{$options}>";
    }

    public function map($name, $options) {
        $arrName = $name;
        $name = $this->parseInputName($name);
        $id   = $this->parseInputId($name);

        $html = '<script type="text/javascript" src="/js/google-maps-v3.js"></script>'.
                $this->asset('js', 'map_input').
                '<div class="map-input">';

        if (isset($options['label'])) {
            $html .= $this->label($name, $options['label']);
        }

        if (! isset($options['value'])) {
            $options['value'] = array(
                'lat' => null,
                'lng' => null,
                'street' => array(
                    'heading' => 0,
                    'pitch'   => 0,
                    'zoom'    => 1
                )
            );
        } else {
            $options['value']['street'] = $options['value'];
        }

        $css = '';
        if (isset($options['width'])) {
            if (preg_match('/^\d+$/', $options['width'])) {
                $options['width'] .= 'px';
            }
            $css .= 'width:'.$options['width'].';';
        }
        if (isset($options['height'])) {
            if (preg_match('/^\d+$/', $options['height'])) {
                $options['height'] .= 'px';
            }
            $css .= 'height:'.$options['width'].';';
        }

        $val = $options['value'];
        $html .= '<div id="'.$id.'" style="'.$css.'" class="left"></div>'.
                '<div class="left" style="margin-left:10px">'.
                    '<fieldset><legend>' . __('mapa') . ':</legend>'.
                    $this->text(array_merge_recursive($arrName, array('map', 'lat')), array(
                        'value' => $val['map']['lat'],
                        'label' => __('lat').":"
                    )).
                    $this->text(array_merge_recursive($arrName, array('map', 'lng')), array(
                        'value' => $val['map']['lng'],
                        'label' => __('lng').":"
                    )).
                    $this->text(array_merge_recursive($arrName, array('map', 'zoom')), array(
                        'value' => $val['map']['zoom'],
                        'label' => __('zoom').":"
                    )).
                    '</fieldset>'.
                    '<fieldset><legend>' . __('street view') . ':</legend>'.
                    $this->text(array_merge_recursive($arrName, array('streetview', 'heading')), array(
                        'value' => $val['streetview']['heading'],
                        'label' => __('heading').":"
                    )).
                    $this->text(array_merge_recursive($arrName, array('streetview', 'pitch')), array(
                        'value' => $val['streetview']['pitch'],
                        'label' => __('pitch').":"
                    )).
                    $this->text(array_merge_recursive($arrName, array('streetview', 'zoom')), array(
                        'value' => $val['streetview']['zoom'],
                        'label' => __('zoom').":"
                    )).'</fieldset>'.
                '</div>'.
                '<script type="text/javascript">new MapInput("'.$id.'", '.json_encode($val).')</script>';

        return $html.'</div>';
    }

    public function image_picker($name0, $options=array()) {
        $name        = $this->parseInputName($name0);
        $id          = $this->parseInputId($name);
        $name_file   = $this->parseInputId(preg_replace('/\]$/', '_file]', $name));
        $name_remove = $this->addInputName($name0, "remove");

        $html = '<div class="image-picker">';
        if (isset($options['label'])) {
            $html .= $this->label($name_file, $options['label']);
        }

        $imageName = @$options['value'];
        $imageBase = @$options['directory'];
        $imageUrl  = preg_replace('/\/+$/', '', $imageBase) . '/' . $imageName;
        
        $html .= $this->hidden($name0, $imageName);
        $html .= '<span class="image">'.
                    '<span class="image-label'.(empty($imageName) ? ' empty' : '').'">'. $imageName .'</span>'.
                    '<span class="image-remove" '.
                        'title="Ukloni sliku" '.
                        'onclick="$(this).siblings(\'img\').attr(\'src\', \''.$imageBase.'\');'.
                                 '$(this).siblings(\'.image-label\').text(\'\').hide();'.
                                 '$(this).siblings(\'[type=file]\').val(\'\');'.
                                 '$(this).parent().siblings(\'[type=hidden]\').val(\'\')"'.
                    '>x</span>'.
                    '<img src="'.$imageUrl.'">'.
                    $this->input($name_file, array(
                        'type' => 'file',
                        'onchange' => "$(this).siblings('.image-label').text(this.value).show()"
                    )).
                '</span>';

        return $html.'</div>';
    }

    public function pageTitle() {
        if (isset($this->controller->pageTitle)) {
            return ": {$this->controller->pageTitle}";
        }
        return '';
    }

    public function flash() {
        $flash = $this->controller->session('flash');
        if (isset($flash)) {
            $this->controller->session_delete('flash');
            return $this->element('flash', array('flash' => (object)$flash));
        }
        return false;
    }

    public function currency($value, $unit='RSD') {
        return number_format((float)$value, 2, ',', '.') . '<span class="currency">'.$unit.'</span>';
    }

    private function parseInputOptions($options) {
        $attrs = "";
        foreach($options as $k=>$v) {
            $attrs .= " {$k}=\"{$v}\"";
        }
        return $attrs;
    }

    private function parseInputName($name) {
        if ($this->form) {
            if (is_array($name)) {
                $name = array_merge(array($this->form), $name);
            } else {
                $name = array($this->form, $name);
            }
        }

        if (is_array($name)) {
            $name = $name[0] . "[" . implode("][", array_slice($name, 1)) . "]";
        }

        return $name;
    }
    private function addInputName($name, $add) {
        $retString = false;
        if (! is_array($name)) {
            $retString = true;
            $name = preg_replace('/\]$/', '', $name);
            $name = preg_split('/\[|\]/', $name, PREG_SPLIT_DELIM_CAPTURE);
        }
        $name[count($name)-1] .= "_{$add}";

        if ($retString) {
            $name = implode('][', $name) . ']';
            $name = preg_replace('/^([^\]]+)\]/', '$1', $name);
        }

        return $name;
    }
    private function parseInputId($name) {
        if (is_array($name)) {
            $name = $this->parseInputName($name);
        }
        $name = preg_replace('/[\]\[]/', '_', $name);
        $name = preg_replace('/^_|_$/',  '',  $name);
        $name = preg_replace('/_{2,}/',  '_', $name);
        return $name;
    }

    private function parsePath($name, $url) {
        if (preg_match('/^[fh]t+ps?/', $name)) {
            return $name;
        } else {
            return $url;
        }
    }

    private function parseAttrs($attrs) {
        if (! empty($attrs)) {
            $ret = '';
            foreach ($attrs as $k=>$v) {
                $ret .= " {$k}=\"{$v}\"";
            }

            return $ret;            
        }

        return '';
    }

}

?>