<?php

// $root is the root after `/www/html` or `/www/htdocs`
// Put `/` if it is the only web server running
// Requires A Trailing `/`

$root = '/';

require_once $_SERVER['DOCUMENT_ROOT'] . $root . 'Classes/Database.php';