<?php

spl_autoload_register(function ($className) {
    if (strpos($className, "Hnk\\HnkUtilBundle") === 0) {
        $path = sprintf("%s/%s.php", dirname(__DIR__), str_replace('\\', "/", str_replace("Hnk\\HnkUtilBundle", "", $className)));
        if (file_exists($path)) {
            require_once $path;
        }
    }
});