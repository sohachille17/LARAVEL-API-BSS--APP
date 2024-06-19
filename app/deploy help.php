<?php

$url = urldecode(
    parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH) ?? ''
);

if($url !== '/' && file_exists(__DIR__."/routes")){
    return false;
}

require_once __DIR__.'/routes/api';