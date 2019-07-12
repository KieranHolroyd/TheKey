<?php

// $root is the root after `/www/html` or `/www/htdocs`
// Put `/` if it is the only web server running
// Requires A Trailing `/`

$root = '/';
var_dump($_SERVER['DOCUMENT_ROOT'] . $root);

require_once $_SERVER['DOCUMENT_ROOT'] . $root . 'Classes/Database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . $root . 'Classes/Config.php';

\App\Config::apply('root', $root);
\App\Config::apply('allow_update_password_plaintext', false);