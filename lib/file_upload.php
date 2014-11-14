<?php

class FileUpload {

    public $error = false;

    private $name;
    private $newData;
    private $originalData;
    private $fieldName;
    private $extensions;
    private $upload = false;
    private $files2remove = array();
    private $files2upload = array();


    public function __construct($name, $newData, $originalData, $fieldName, $extensions=null) {
        $this->name         = $name;
        $this->newData      = (object)$newData;
        $this->originalData = (object)$originalData;
        $this->fieldName    = $fieldName;
        $this->extensions   = $extensions;

        $_file = @$_FILES[$name];
        if (! empty($_file['name'])) {
            $this->upload = $_file;
        }

        $this->validate();
    }

    public function validate() {
        if ($this->upload) {
            if (! $this->upload['error']) {
                
                if (isset($this->extensions)) {
                    $ext = strtolower(pathinfo($this->upload['name'], PATHINFO_EXTENSION));
                    if (! in_array($ext, $this->extensions)) {
                        $this->error = 'Odabrani fajl nije validan. '.
                                       'Dozvoljeni formati su: '. implode(', ', $this->extensions);
                        return;
                    }
                }

                if ($this->originalData) {
                    $fieldName = $this->fieldName;
                    $this->files2remove[] = $this->originalData->{$fieldName};
                }

                $this->files2upload[] = $this->upload;
            } else {
                $maxUploadSize = APP::parseFileSize(ini_get('upload_max_filesize'));
                $maxPostSize   = APP::parseFileSize(ini_get('post_max_size'));
                $maxSize = $maxPostSize < $maxUploadSize ? $maxPostSize : $maxUploadSize;

                $this->error = 'Slanje fajla nije uspelo. Veličina fajla ne sme biti veća od '.APP::humanizeFileSize($maxSize).".";
            }
        }
        
        if ($this->originalData && $this->newData) {
            $condition = @$this->originalData->{$this->fieldName} != @$this->newData->{$this->fieldName};
            if ($condition) {
                $this->files2remove[] = $this->originalData->{$this->fieldName};
            }
        }
    }

    public function process($base) {
        foreach ($this->files2remove as $removeme) {
            @unlink($base . DS . $removeme);
        }

        if (! $this->upload) {
            return;
        }

        foreach ($this->files2upload as $uploadme) {
            if (! is_dir($base)) {
                @mkdir($base, 0777);
            }

            $dest = $base . DS . $uploadme['name'];
            move_uploaded_file($uploadme['tmp_name'], $dest);
        }
    }

    public function name() {
        if ($this->upload) {
            return $this->upload['name'];
        /*} elseif(isset($this->newData->{$this->fieldName})) {
            return $this->newData->{$this->fieldName};*/
        } else {
            return $this->newData->{$this->fieldName};
        }
    }
}

?>