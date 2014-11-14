<?php

define('DS', DIRECTORY_SEPARATOR);

function __($string) {
    return Locale::translate($string);
}

?>